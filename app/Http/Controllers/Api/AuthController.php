<?php

namespace PockDoc\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Http\Requests\PhoneNumberRequest;
use PockDoc\Http\Requests\PhoneNumberTokenRequest;
use PockDoc\Models\FCMId;
use PockDoc\Models\SMSToken;
use PockDoc\Models\User;
use PockDoc\Notifications\PhoneSMSToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class AuthController extends Controller
{

    private $lifetime = 5;
    private $interval = 1;

    public function send_sms_token(PhoneNumberRequest $request)
    {
        $this->removeOldTokens();

        return \DB::transaction(function () use ($request) {

            $user = User::query()->where('phone_number', $request->phone_number)
                ->first();
            $reset = $request->get('reset', false);
            $doctor = $request->get('doctor', false);
            $created = false;

            if ($user) {
                if (!$doctor && $user->doctor) {
                    throw new AuthorizationException('You need application for doctors');
                }
                if ($doctor && !$user->doctor) {
                    throw new AuthorizationException('You need application for pacients');
                }
            }

            $time = Carbon::now()->subMinutes($this->interval);
            $token = SMSToken::query()
                ->where('created_at', '>', $time)
                ->first();

            if ($token) {
                throw new TooManyRequestsHttpException($time->diffInSeconds($token->created_at), 'Too many request for SMS sending');
            }

            if (env('APP_ENV') == 'local' || $request->phone_number == '7073757080') {
                $token = SMSToken::create([
                    'fcm_id' => $request->fcm_id,
                    'phone_number' => $request->phone_number,
                    'token' => '000000'
                ]);
                \Log::info('local send');
            } else {
                $token = SMSToken::create([
                    'fcm_id' => $request->fcm_id,
                    'phone_number' => $request->phone_number,
//                    'token' => rand(100000, 999999)
                    'token' => 111111
                ]);

//                $token->notify(new PhoneSMSToken($token->token));
//                \Log::info('production send');
            }
            \Log::info('ccc');
            if ($user && (!$user->password || $reset)) {
                \Log::info('updated');
                $user->update([
                    'password' => \Hash::make($token->token)
                ]);

            } else if (!$user) {
                \Log::info('created');
                $user = User::create([
                    'phone_number' => $request->phone_number,
                    'password' => \Hash::make($token->token)
                ]);
                if ($user)
                    $created = true;
            }
            \Log::info('end');
            return compact('created');
        });
    }

    private function removeOldTokens()
    {
        $time = Carbon::now()->subMinutes($this->lifetime);
        SMSToken::query()
            ->where('created_at', '<', $time)
            ->delete();
    }

    public function login(PhoneNumberTokenRequest $request)
    {
        $this->removeOldTokens();
        $user = User::query()
            ->where('phone_number', $request->phone_number)
            ->with('doctor')
            ->with('card')
            ->first();

        if ($user) {
            if (\Hash::check($request->token, $user->password)) {
                $fcmId = FCMId::query()->where('id', $request->fcm_id)->first();
                if (!$fcmId) {
                    FCMId::create([
                        'id' => $request->fcm_id,
                        'user_id' => $user->id
                    ]);
                } else {
                    $fcmId->update([
                        'user_id' => $user->id
                    ]);
                }
                return $user;
            }
        }

        $token = SMSToken::query()
            ->where('fcm_id', $request->fcm_id)
            ->where('phone_number', $request->phone_number)
            ->where('token', $request->token)
            ->first();

        if (!$token) {
            throw new ModelNotFoundException('Token not found');
        }

        return \DB::transaction(function () use ($request, $token) {
            $user = User::query()
                ->where('phone_number', $request->phone_number)
                ->with('doctor')
                ->with('card')
                ->first();
//            $user = User::query()
//                ->where('phone_number', $request->phone_number)
//                ->with('doctor')
//                ->with('card')
//                ->first();

            if (!$user) {
                $user = User::create([
                    'phone_number' => $request->phone_number
                ]);
//                throw new ModelNotFoundException('User not found');
            }

//            if(!\Hash::check($request->token, $user->password)) {
//                $validator = \Validator::make([], []);
//                $validator->after(function($validator) {
//                    $validator->errors()->add('token', 'validation.token');
//                });
//                $validator->validate();
//            }


            $fcmId = FCMId::query()->where('id', $request->fcm_id)->first();
            if (!$fcmId) {
                FCMId::create([
                    'id' => $request->fcm_id,
                    'user_id' => $user->id
                ]);
            } else {
                $fcmId->update([
                    'user_id' => $user->id
                ]);
            }
            $token->delete();

            return $user;
        });
    }

    public function logout(Request $request)
    {
        FCMId::find($request->fcm_id)->delete();
        return null;
    }

}

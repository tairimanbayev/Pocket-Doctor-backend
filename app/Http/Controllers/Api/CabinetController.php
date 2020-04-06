<?php

namespace PockDoc\Http\Controllers\Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Http\Requests\Api\StoreProfileRequest;
use PockDoc\Http\Requests\StoreLocationRequest;
use PockDoc\Models\Card;
use PockDoc\Models\Doctor;
use PockDoc\Models\FCMId;
use PockDoc\Models\User;

class CabinetController extends Controller
{

    public function profile(Request $request)
    {
        return User::query()
            ->where('id', $request->user()->id)
            ->with('doctor')
            ->with('card')
            ->firstOrFail();
    }

    public function updateProfile(StoreProfileRequest $request)
    {
        $user = $request->user();
        $user->update($request->all());
        return $user;
    }

    public function unsetCard(Request $request)
    {
        $user = $request->user();
        $user->update(['card_id' => null]);
        return null;
    }

    public function setCard(Request $request, Card $card)
    {
        $user = $request->user();
        if ($card->user_id != $user->id) {
            throw new ModelNotFoundException('Card not found');
        }
        $user->update(['card_id' => $card->id]);
        return null;
    }

    public function logout(Request $request)
    {
        $fcm_id = $request->get('fcm_id');
        FCMId::find($fcm_id)->delete();
        return null;
    }

    public function update_location(StoreLocationRequest $request)
    {
        \Log::info('userid' . $request->user()->id);
        Doctor::query()->where('user_id', $request->user()->id)
            ->update($request->only(['latitude', 'longitude']));
        return null;
    }

}

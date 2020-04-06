<?php

namespace PockDoc\Http\Controllers\Api;

use Carbon\Carbon;
use CloudPayments\Exception\PaymentException;
use CloudPayments\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Http\Requests\Api\OnlyDoctorsRequest;
use PockDoc\Http\Requests\Api\PaginationRequest;
use PockDoc\Http\Requests\Api\QuestionsRequest;
use PockDoc\Http\Requests\Api\StoreFeedbackRequest;
use PockDoc\Http\Requests\Api\StoreIllnessRequest;
use PockDoc\Http\Requests\Api\StoreVisitRequest;
use PockDoc\Models\Feedback;
use PockDoc\Models\Illness;
use PockDoc\Models\Price;
use PockDoc\Models\Question;
use PockDoc\Models\SkipVisit;
use PockDoc\Models\Visit;
use PockDoc\Models\VisitCard;
use PockDoc\Models\VisitQuestion;
use PockDoc\Notifications\VisitAcceptedNotification;
use PockDoc\Notifications\VisitCreatedNotification;
use PockDoc\Notifications\VisitFinishedNotification;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(PaginationRequest $request)
    {
        $history = $request->get('history', false);
        $active = $request->get('active', false);
        $user = $request->user();
        if ($user->doctor) {
            $query = Visit::query()
                ->with(['cards', 'cards.user', 'doctor', 'doctor.user', 'doctor.clinic', 'address', 'questions', 'payment_card'])
                ->whereNotExists(function ($query) use ($user) {
                    $query->select(\DB::raw(1))
                        ->from('skip_visit')
                        ->whereRaw('skip_visit.visit_id = visits.id')
                        ->where('skip_visit.doctor_id', $user->doctor->id);
                });

            if ($request->card_id) {
                $query->join('visit_card', 'visit_card.visit_id', '=', 'visits.id')
                    ->whereNotNull('visits.doctor_id')
                    ->where('visit_card.card_id', intval($request->card_id));
            } else {
                if ($active) {
                    $query = $query
                        ->orderBy('accepted_at', 'desc')
                        ->where('doctor_id', $user->doctor->id)
                        ->whereNull('finished_at');
                } else if ($history) {
                    $query = $query
                        ->orderBy('accepted_at', 'desc')
                        ->where('doctor_id', $user->doctor->id)
                        ->whereNotNull('finished_at');
                } else {
                    $query = $query
                        ->where('role', $user->doctor->role)
                        ->whereNull('doctor_id');
                }
            }
        } else {
            $query = Visit::query()
                ->join('visit_card', 'visit_card.visit_id', '=', 'visits.id')
                ->join('cards', 'visit_card.card_id', '=', 'cards.id')
                ->where('cards.user_id', '=', $request->user()->id)
                ->with(['cards', 'address', 'questions', 'payment_card', 'doctor', 'doctor.user', 'doctor.user.card', 'doctor.clinic']);
            if ($history) {
                $query = $query
                    ->whereNotNull('finished_at');
            } else {
                $query = $query->whereNull('finished_at');
            }
        }
        \Log::info($query->toSql());
        $count = $query->count();
        return [
            'data' => $query
                ->orderBy('created_at', 'desc')
                ->limit($request->limit)
                ->offset($request->offset)
                ->get(['visits.*'])->toArray(),
            'last_page' => ($count <= ($request->limit + $request->offset))
        ];
    }

    public function price(Request $request)
    {
        $query = Price::query();
        if ($role = $request->get('role')) {
            $query = $query->where('role', $role);
        }
        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreVisitRequest $request
     * @return array
     * @throws \Throwable
     */
    public function store(StoreVisitRequest $request)
    {
        \Log::info('there1');
        try {
            return \DB::transaction(function () use ($request) {
                $visit = Visit::create($request->all());

                $price = $visit->priceRole;
                if(!$price) {
                    \Validator::make([], [])->after(function($validator) {
                        $validator->errors()->add('payment', 'Не найдена цена для данной категории врача');
                    })->validate();
                }
                $totalPrice = 0;
                foreach ($request->get('card_id', []) as $card_id) {
                    VisitCard::create([
                        'visit_id' => $visit->id,
                        'card_id' => $card_id
                    ]);
                    $totalPrice += $totalPrice ? ($price->price2 ?: $price->price1) : $price->price1;
                }
                foreach ($request->get('question_id', []) as $question_id) {
                    VisitQuestion::query()
                        ->where(['visit_id' => $visit->id,
                            'question_id' => $question_id])->delete();
                    VisitQuestion::create([
                        'visit_id' => $visit->id,
                        'question_id' => $question_id
                    ]);
                }
                $visit->update(['price' => $totalPrice]);
                if ($visit->payment_card_id) {
                    $paymentCard = $visit->payment_card;
                    $client = new \CloudPayments\Manager(config('services.cloudpayments.public_key'), config('services.cloudpayments.private_key'));
                    $transaction = $client->chargeToken($totalPrice, 'KZT', $paymentCard->account_id, $paymentCard->reference);
                }
                $visit->notify(new VisitCreatedNotification($visit));
                return $visit;
            });
        } catch (RequestException $e) {
            \Log::info('request exception');
            \Validator::make([], [])->after(function($validator) use ($e) {
                $validator->errors()->add('payment', $e->getMessage());
            })->validate();
        } catch (PaymentException $e) {
            \Log::info('request exception');
            \Validator::make([], [])->after(function($validator) use ($e) {
                $validator->errors()->add('payment', $e->getCardHolderMessage());
            })->validate();
        }
    }

    public function questions(QuestionsRequest $request)
    {
        return Question::query()
            ->where('role', $request->role)
            ->get();
    }

    public function accept(OnlyDoctorsRequest $request, Visit $visit)
    {
        if ($visit->doctor_id) {
            throw new NotAcceptableHttpException('Visit already accepted');
        }
        return \DB::transaction(function () use ($request, $visit) {
            $visit->update([
                'doctor_id' => $request->user()->doctor->id,
                'accepted_at' => Carbon::now()
            ]);
            $visit->cards[0]->user->notify(new VisitAcceptedNotification($visit));
            return $visit;
        });
    }

    public function skip(OnlyDoctorsRequest $request, Visit $visit)
    {
        return \DB::transaction(function () use ($request, $visit) {
            SkipVisit::create([
                'doctor_id' => $request->user()->doctor->id,
                'visit_id' => $visit->id
            ]);
            return null;
        });
    }

    public function illnesses(Request $request, Visit $visit)
    {
        return $visit->illnesses;
    }

    public function all_illnesses(Request $request)
    {
        $query = Illness::query();
        $some = false;
        if ($visit_id = $request->get('visit_id')) {
            $query = $query->where('visit_id', $visit_id);
            $some = true;
        }
        if ($card_id = $request->get('card_id')) {
            $query = $query->where('card_id', $card_id);
            $some = true;
        }
        if (!$some) {
            $query = $query->join('cards', 'cards.id', '=', 'illnesses.card_id')
                ->where('cards.user_id', $request->user()->id);
        }

        return $query
            ->with(['card', 'visit', 'visit.doctor', 'visit.doctor.clinic', 'medicines'])
            ->get(['illnesses.*']);
    }

    public function make_illness(StoreIllnessRequest $request, Visit $visit)
    {
        $illness = Illness::create(array_merge($request->all(), [
            'visit_id' => $visit->id
        ]));
        return $illness;
    }

    public function update_illness(StoreIllnessRequest $request, Illness $illness)
    {
        $illness->update($request->all());
        return $illness;
    }

    public function delete_illness(Illness $illness)
    {
        $illness->delete();
        return null;
    }

    public function finish(Request $request, Visit $visit)
    {
        $visit->finished_at = Carbon::now();
        $visit->update();
        $visit->cards[0]->user->notify(new VisitFinishedNotification($visit));
        return $visit;
    }

    public function doctor_location(Request $request, Visit $visit)
    {
        $doctor = $visit->doctor;
        return [
            'latitude' => $doctor->latitude,
            'longitude' => $doctor->longitude
        ];
    }

    public function feedback(StoreFeedbackRequest $request, Visit $visit)
    {
        $feedback = Feedback::create(array_merge($request->all(), [
            'user_id' => $request->user()->id,
            'visit_id' => $visit->id
        ]));
        $visit->doctor->update([
            'rating' => \DB::selectOne('select ifnull(avg(f.mark), 5) from feedback f join visits v on v.id = f.visit_id where doctor_id = ' . $visit->doctor_id . ';')
        ]);
        return $feedback;
    }

}

<?php

namespace PockDoc\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PockDoc\Http\Requests\Api\StoreIllnessRequest;
use PockDoc\Http\Requests\PhotoRequest;
use PockDoc\Http\Requests\StoreCardRequest;
use PockDoc\Models\Card;
use Illuminate\Http\Request;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Models\Illness;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->cards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCardRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreCardRequest $request)
    {
        \Log::info('card');
        $data = $request->all();
        $user = $request->user();
        $data['birthday'] = Carbon::createFromFormat('d.m.Y', $data['birthday'])->format('d.m.Y');
        $data['user_id'] = $user->id;
        return \DB::transaction(function() use ($data, $request, $user) {
            $card = Card::create($data);
            if(!$user->card_id) {
                $user->update([
                    'card_id' => $card->id
                ]);
            }
            return $card;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  \PockDoc\Models\Card $card
     * @return Card
     */
    public function show(Request $request, Card $card)
    {
        if($card->user_id != $request->user()->id && !$request->user()->doctor) {
            throw new ModelNotFoundException('Card not found');
        }
        return $card;
    }

    public function photo(Request $request, Card $card)
    {
        if($card->user_id != $request->user()->id && !$request->user()->doctor) {
            throw new ModelNotFoundException('Card not found');
        }
        if(!file_exists(storage_path("app/photos/{$card->id}.jpg"))) {
            throw new ModelNotFoundException('Card not found');
        }
        return response()->download(storage_path("app/photos/{$card->id}.jpg"));
    }

    public function updatePhoto(PhotoRequest $request, Card $card)
    {
        if($card->user_id != $request->user()->id) {
            throw new ModelNotFoundException('Card not found');
        }
        if($request->photo) {
            $request->photo->storeAs('photos', $card->id . '.jpg');
        } else {
            unlink(storage_path("app/photos/{$card->id}.jpg"));
        }
        return null;
    }

    public function illnesses(Request $request, Card $card) {
        if($card->user_id != $request->user()->id && !$card->doctor) {
            throw new ModelNotFoundException('Card not found');
        }
        return $card->illnesses;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|StoreCardRequest $request
     * @param  \PockDoc\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCardRequest $request, Card $card)
    {
        $data = $request->all();
        $user = $request->user();
        $data['user_id'] = $card->user_id;
        //$data['birthday'] = Carbon::createFromFormat('d.m.Y', $data['birthday']);
        //$data['user_id'] = $user->id;
        return \DB::transaction(function() use ($data, $request, $user, $card) {
            $card->update($data);
            if(!$user->card_id) {
                $user->update([
                    'card_id' => $card->id
                ]);
            }
            return $card;
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \PockDoc\Models\Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Card $card)
    {
        if($card->user_id != $request->user()->id) {
            throw new ModelNotFoundException('Card not found');
        }
        $card->delete();
        return null;
    }
}

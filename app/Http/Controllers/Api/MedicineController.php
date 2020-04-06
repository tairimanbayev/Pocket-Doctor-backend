<?php

namespace PockDoc\Http\Controllers\Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Http\Requests\Api\OnlyDoctorsRequest;
use PockDoc\Http\Requests\Api\OnlyPacientsRequest;
use PockDoc\Http\Requests\StoreMedicineRequest;
use PockDoc\Models\Medicine;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Medicine::query()
            ->join('illnesses', 'illnesses.id', '=', 'medicines.illness_id')
            ->join('visits', 'visits.id', '=', 'illnesses.visit_id')
            ->join('visit_card', 'visit_card.visit_id', '=', 'visits.id')
            ->join('cards', 'visit_card.card_id', '=', 'cards.id');

        $some = false;
        if ($visit_id = $request->get('visit_id')) {
            $query = $query->where('visits.id', $visit_id);
            $some = true;
        }
        if ($card_id = $request->get('card_id')) {
            $query = $query->where('cards.id', $card_id);
            $some = true;
        }
        if(!$some) {
            $query = $query->where('cards.user_id', '=', $request->user()->id);
        }

        return $query->get(['medicines.*']);
    }

    public function store(Request $request)
    {
        return Medicine::create($request->all());
    }

    public function show(Request $request, Medicine $medicine)
    {
        return $medicine;
    }

    public function update(Request $request, Medicine $medicine)
    {
        $medicine->update($request->all());
        return $medicine;
    }

    public function destroy(Request $request, Medicine $medicine)
    {
        $medicine->delete();
        return null;
    }
}

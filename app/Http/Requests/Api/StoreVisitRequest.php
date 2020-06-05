<?php

namespace PockDoc\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role' => 'required|in:' . implode(',', array_keys(trans('roles'))),
            'address_id' => 'required|exists:addresses,id',
            'payment_card_id' => 'exists:payment_cards,id',
            'visit_at' => 'required|date',
            'card_id' => 'required',
            'card_id.*' => 'required|exists:cards,id',
            'question_id.*' => 'required|exists:questions,id',
        ];
    }
}

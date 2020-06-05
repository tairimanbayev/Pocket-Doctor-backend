<?php

namespace PockDoc\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreIllnessRequest extends FormRequest
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
            'card_id' => 'required|exists:cards,id',
            'title' => 'required',
            'description' => 'required',
        ];
    }
}

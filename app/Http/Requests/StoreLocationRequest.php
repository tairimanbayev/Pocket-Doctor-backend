<?php

namespace PockDoc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PockDoc\Http\Requests\Api\OnlyDoctorsRequest;

class StoreLocationRequest extends OnlyDoctorsRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }
}

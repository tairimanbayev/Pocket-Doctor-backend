<?php

namespace PockDoc\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OnlyDoctorsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
//        return $this->user()->doctor;
    }

    public function rules()
    {
        return [];
    }

}

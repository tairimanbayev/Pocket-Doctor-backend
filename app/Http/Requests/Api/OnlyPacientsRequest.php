<?php

namespace PockDoc\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OnlyPacientsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->user()->doctor;
    }

}

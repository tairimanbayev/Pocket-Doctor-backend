<?php

namespace PockDoc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PhoneNumberTokenRequest
 * @property string token
 * @package PockDoc\Http\Requests
 */
class PhoneNumberTokenRequest extends PhoneNumberRequest
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

    public function expectsJson()
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
        return array_merge(parent::rules(), [
            'token' => 'required|numeric'
        ]);
    }
}

<?php

namespace PockDoc\Http\Requests;

/**
 * Class PhoneNumberRequest
 * @property string fcm_id
 * @property string phone_number
 * @package PockDoc\Http\Requests
 */
class PhoneNumberRequest extends FcmIdRequest
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
        return array_merge(parent::rules(), [
            'phone_number' => 'required|phone_number'
        ]);
    }
}

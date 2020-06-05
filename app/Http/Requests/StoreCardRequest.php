<?php

namespace PockDoc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreCardRequest
 * @package PockDoc\Http\Requests
 */
class StoreCardRequest extends FormRequest
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
            'first_name' => 'required|max:30',
            'last_name' => 'required|max:30',
            'middle_name' => 'max:30',
            'birthday' => 'required|date_format:d.m.Y',
            'gender' => 'boolean',
            'height' => 'numeric',
            'weight' => 'numeric',
        ];
    }
}

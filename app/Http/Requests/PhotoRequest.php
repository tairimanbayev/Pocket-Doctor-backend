<?php

namespace PockDoc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PhotoRequest
 * @package PockDoc\Http\Requests
 * @property \Illuminate\Http\UploadedFile photo
 */
class PhotoRequest extends FormRequest
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
            'photo' => 'file|max:512|image|dimensions:min_width=200,min_height=200,max_width=1500,max_height=1500'
        ];
    }
}

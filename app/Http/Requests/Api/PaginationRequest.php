<?php

namespace PockDoc\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PaginationRequest
 * @package PockDoc\Http\Requests\Api
 * @property int limit
 * @property int offset
 */
class PaginationRequest extends FormRequest
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
            'limit' => 'required|numeric',
            'offset' => 'required|numeric',
        ];
    }
}

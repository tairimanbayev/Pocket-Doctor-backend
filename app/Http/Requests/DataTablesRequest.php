<?php

namespace PockDoc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DataTablesRequest
 * @package PockDoc\Http\Requests
 * @property int draw
 * @property int start
 * @property int length
 */
class DataTablesRequest extends FormRequest
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
            //
        ];
    }
}

<?php

namespace App\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;

class GradeCreate extends FormRequest
{

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
            'name' => 'required|string',
            'mark_from' => 'required|numeric:max:100|min:0',
            'mark_to' => 'required|numeric:max:100|min:0',
            'point' => 'required|numeric|max:10|min:0',
            'credit' => 'required|numeric|max:5:min:0',
        ];
    }

    public function attributes()
    {
        return  [
            'mark_from' => 'Mark From',
            'mark_to' => 'Mark To',
        ];
    }
}

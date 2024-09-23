<?php

namespace App\Http\Requests\Division;

use Illuminate\Foundation\Http\FormRequest;

class DivisionCreate extends FormRequest
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
            'points_from' => 'required|numeric',
            'points_to' => 'required|numeric',
        ];
    }

    public function attributes()
    {
        return  [
            'points_from' => 'Points From',
            'points_to' => 'Points To',
        ];
    }
}

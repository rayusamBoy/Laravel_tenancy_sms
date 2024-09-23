<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserBlockedState extends FormRequest
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
            'id' => 'required|integer|bail',
            'blocked' => 'required|in:0,1',
        ];
    }
}

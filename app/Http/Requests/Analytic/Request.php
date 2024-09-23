<?php

namespace App\Http\Requests\Analytic;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
            'period_number' => 'integer|min:1|sometimes',
            'period_name' => 'string|sometimes',
        ];
    }

    public function attributes()
    {
        return  [];
    }
}

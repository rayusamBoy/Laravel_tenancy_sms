<?php

namespace App\Http\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;

class NoticeUpdate extends FormRequest
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
        return  [
            'title' => 'required|string|min:3|max:150',
            'body' => 'required|string|min:7|max:500',
        ];
    }

    public function attributes()
    {
        return  [
            'title' => 'notice title',
            'body' => 'notice body or content',
        ];
    }
}

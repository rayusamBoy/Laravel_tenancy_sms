<?php

namespace App\Http\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;

class NoticeSetAsViewed extends FormRequest
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
            'id' => 'required|distinct|integer',
        ];
    }

}

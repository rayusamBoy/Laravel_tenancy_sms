<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class TicketCreate extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'department' => 'required|string',
            'priority' => 'required|string',
            'subject' => 'required|string|max:70',
            'message' => 'required|string|max:400',
            'category_id' => 'required|integer',
            'labels_ids' => 'sometimes|nullable|array',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'Category',
            'labels_ids' => 'Labels',
        ];
    }
}

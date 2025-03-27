<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class TicketPropertiesCreate extends FormRequest
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
            'categories.name.*' => 'required|string|max:255',
            'categories.description.*' => 'required|string',
            'categories.is_visible.*' => 'required|boolean',
            'labels.name.*' => 'required|string|max:255',
            'labels.description.*' => 'required|string',
            'labels.is_visible.*' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'categories.name.*' => 'Category name',
            'categories.description.*' => 'Category description',
            'categories.is_visible.*' => 'Category visibility',
            'labels.name.*' => 'Label name',
            'labels.description.*' => 'Label description',
            'labels.is_visible.*' => 'Label visibility',
        ];
    }
}

<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdate extends FormRequest
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
            'name' => 'required|string|max:150',
            'my_class_id' => 'required|integer|exists:my_classes,id',
            'description' => 'sometimes|nullable|max:255',
            'author' => 'required|string|max:150',
            'book_type' => 'sometimes|nullable|max:100',
            'url' => 'sometimes|nullable|url',
            'total_copies' => 'sometimes|nullable|integer|min:1',
            'issued_copies' => 'sometimes|nullable|integer|min:1',
            'location' => 'sometimes|nullable|string|max:250'
        ];
    }

    public function attributes()
    {
        return  [
            'my_class_id' => 'Class',
            'name' => 'Book name',
        ];
    }
}

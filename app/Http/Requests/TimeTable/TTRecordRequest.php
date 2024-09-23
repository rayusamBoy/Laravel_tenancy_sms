<?php

namespace App\Http\Requests\TimeTable;

use Illuminate\Foundation\Http\FormRequest;

class TTRecordRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        if($this->method() === 'POST'){
            return [
                'name' => 'required|string|min:3|unique:time_table_records',
                'my_class_id' => 'required',
                'section_id' => 'nullable',
            ];
        }

        return [
            'name' => 'required|string|min:3|unique:time_table_records,name,'.$this->ttr,
            'my_class_id' => 'required',
            'section_id' => 'nullable',
        ];
    }

    public function attributes()
    {
        return  [
            'my_class_id' => 'Class',
            'section_id' => 'Section',
        ];
    }

}

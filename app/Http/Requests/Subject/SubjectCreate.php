<?php

namespace App\Http\Requests\Subject;

use App\Helpers\Qs;
use App\Models\StudentRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class SubjectCreate extends FormRequest
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
            'name' => 'required|string|min:3',
            'my_class_id' => 'required',
            'section_id' => 'nullable|prohibited_unless:students_ids,null',
            'students_ids' => 'nullable',
            'teacher_id' => 'required',
            'slug' => 'nullable|string|min:2|max:7',
        ];
    }

    public function attributes()
    {
        return  [
            'my_class_id' => 'class',
            'teacher_id' => 'teacher',
            'section_id' => 'section',
            'students_ids' => 'students',
            'slug' => 'short name',
        ];
    }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $input['teacher_id'] = $input['teacher_id'] ? Qs::decodeHash($input['teacher_id']) : NULL;

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
}

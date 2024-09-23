<?php

namespace App\Http\Requests\Subject;

use App\Helpers\Qs;
use Illuminate\Foundation\Http\FormRequest;

class SubjectRecordUpdate extends FormRequest
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
            'students_ids' => 'nullable',
            'teacher_id' => 'sometimes|nullable|exists:users,id',
            'slug' => 'nullable|string|min:2|max:7',
            'core' => 'required|in:0,1'
        ];
    }

    public function attributes()
    {
        return  [
            'teacher_id' => 'Teacher',
            'students_ids' => 'students',
            'slug' => 'Short Name',
            'core' => 'Core Subject'
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

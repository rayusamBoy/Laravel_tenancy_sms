<?php

namespace App\Http\Requests\Exam;

use App\Helpers\Mk;
use Illuminate\Foundation\Http\FormRequest;

class ExamCreate extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function getValidatorInstance()
    {
        $input = $this->all();
        $input['maximum_exam_ca_denominator'] = $input['cw_denominator'] + $input['hw_denominator'] + $input['tt_denominator'] + $input['tdt_denominator'];
        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'term' => 'required|numeric',
            'exam_denominator' => 'required|integer|max:100|min:0',
            'exam_student_position_by_value' => 'required|string',
            'ca_student_position_by_value' => 'sometimes|string',
            'cw_denominator' => 'nullable|integer|required_if:category_id,' . Mk::getAnnualExamCategoryId() . ',' . Mk::getTerminalExamCategoryId(),
            'hw_denominator' => 'nullable|integer|required_if:category_id,' . Mk::getAnnualExamCategoryId() . ',' . Mk::getTerminalExamCategoryId(),
            'tt_denominator' => 'nullable|integer|gt:cw_denominator|gt:hw_denominator|required_if:category_id,' . Mk::getAnnualExamCategoryId() . ',' . Mk::getTerminalExamCategoryId(),
            'tdt_denominator' => 'nullable|integer|gt:tt_denominator|required_if:category_id,' . Mk::getAnnualExamCategoryId() . ',' . Mk::getTerminalExamCategoryId(),
            'category_id' => 'required|numeric',
            'class_type_id' => 'required|numeric',
            // Runtime added attribute.
            'maximum_exam_ca_denominator' => 'required|integer|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'exam category',
            'class_type_id' => 'class type',
            'cw_denominator' => 'class work denominator',
            'hw_denominator' => 'home work denominator',
            'tt_denominator' => 'topic test denominator',
            'tdt_denominator' => 'termed test denominator',
            'maximum_exam_ca_denominator' => 'the sum of class work, home work, topic test, and termed test denominators',
        ];
    }
}

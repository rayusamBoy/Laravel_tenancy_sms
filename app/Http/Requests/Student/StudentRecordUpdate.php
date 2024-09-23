<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Helpers\Qs;

class StudentRecordUpdate extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function getRouteUserIdParameter()
    {
        return Qs::decodeHash($this->input('student'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:6|max:150',
            'gender' => 'required|string',
            'phone' => 'sometimes|nullable|string|min:6|max:20',
            'email' => 'sometimes|nullable|email|max:100|unique:users,id',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:4|max:120',
            'bg_id' => 'sometimes|nullable',
            'my_class_id' => 'required',
            'section_id' => 'required',
            'state_id' => 'required',
            'lga_id' => 'required',
            'nal_id' => 'required',
            'my_parent_id' => 'sometimes|nullable',
            'dorm_id' => 'sometimes|nullable',
            'ps_name' => 'required|string|max:40',
            'ss_name' => 'required_if:my_class_id,5,6|max:40',
            'birth_certificate' => 'sometimes|nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'disability' => 'sometimes|nullable',
            'chp' => 'sometimes|nullable|string',
            'food_taboos' => 'sometimes|nullable|string',
            'p_status' => 'required|string', 
            'adm_no' => ['sometimes', 'nullable', 'regex:/^[a-zA-Z0-9\s\-\/]+$/', 'min:4', 'max:20', 'unique:student_records,id'],
        ];
    }

    public function attributes()
    {
        return  [
            'nal_id' => 'nationality',
            'dorm_id' => 'dormitory',
            'state_id' => 'state',
            'lga_id' => 'LGA',
            'bg_id' => 'blood group',
            'my_parent_id' => 'Parent',
            'my_class_id' => 'class',
            'section_id' => 'section',
            'ps_name' => 'primary school name',
            'ss_name' => 'secondary school name',
            'chp' => 'chronic Health Problem',
            'p_status' => 'parent status',
            'adm_no' => 'admission number',
        ];
    }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $input['my_parent_id'] = $input['my_parent_id'] ? Qs::decodeHash($input['my_parent_id']) : NULL;

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
}

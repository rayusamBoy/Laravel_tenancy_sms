<?php

namespace App\Http\Requests;

use App\Helpers\Usr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdate extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function getAuthUserId()
    {
        return Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:3|max:120',
            'bank_name' => 'sometimes|nullable|required_with:bank_acc_no',
            'file_number' => 'sometimes|nullable|string|max:15',
            'phone' => ['sometimes', 'nullable', 'string', 'min:6', 'max:20', Rule::unique('users')->ignore($this->getAuthUserId(), 'id')],
            'phone2' => ['sometimes', 'nullable', 'string', 'min:6', 'max:20', Rule::unique('users')->ignore($this->getAuthUserId(), 'id')],
            'email' => ['sometimes', 'nullable', 'email', 'max:100', Rule::unique('users', 'email')->ignore($this->getAuthUserId(), 'id')],
            'username' => ['sometimes', 'nullable', 'alpha_dash', 'min:8', 'max:100', Rule::unique('users', 'username')->ignore($this->getAuthUserId(), 'id')],
            'primary_id' => ['sometimes', 'nullable', 'alpha_num', 'min:3', 'max:20', Rule::unique('users', 'primary_id')->ignore($this->getAuthUserId(), 'id')],
            'secondary_id' => ['sometimes', 'nullable', 'alpha_num', 'min:3', 'max:20', Rule::unique('users', 'secondary_id')->ignore($this->getAuthUserId(), 'id')],
            'bank_acc_no' => ['sometimes', 'nullable', 'string', 'min:5', 'max:11', Rule::unique('staff_records', 'bank_acc_no')->ignore($this->getAuthUserId(), 'user_id')],
            'tin_number' => ['sometimes', 'nullable', 'alpha_num', 'min:3', 'max:15', Rule::unique('staff_records', 'tin_number')->ignore($this->getAuthUserId(), 'user_id')],
            'ss_number' => ['sometimes', 'nullable', 'string', 'min:3', 'max:15', Rule::unique('staff_records', 'ss_number')->ignore($this->getAuthUserId(), 'user_id')],
            'licence_number' => ['sometimes', 'nullable', 'string', 'max:10', Rule::unique('staff_records', 'licence_number')->ignore($this->getAuthUserId(), 'user_id')],
            'dob' => 'sometimes|nullable|date_format:' . Usr::getDateFormat(),
            // The regex allows for alphabetic characters and spaces within each subject (for example, "Literature in English" is valid). 
            // Each subject can be followed by a comma and optional spaces, before the next valid subject.
            'subjects_studied' => 'sometimes|nullable|max:250|regex:/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]+)*$/',
        ];
    }

    public function attributes()
    {
        return [
            'nal_id' => 'nationality',
            'state_id' => 'state',
            'lga_id' => 'LGA',
            'phone2' => 'telephone',
            'primary_id' => 'primary ID card number',
            'secondary_id' => 'secondary ID card number',
            'licence_number' => 'licence number',
            'file_number' => 'file number',
            'bank_acc_no' => 'bank account number',
            'tin_number' => 'tin number',
            'ss_number' => 'social security number',
            'dob' => 'date of birth',
        ];
    }
}

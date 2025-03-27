<?php

namespace App\Http\Requests;

use App\Helpers\Usr;
use App\Rules\StartsWithProperPhoneCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize()
    {
        return true;
    }

    protected function getRouteUserIdParameter()
    {
        // If not data store
        if ($this->method() !== "POST") {
            // Get id of the user being updated
            return Usr::decodeHash($this->route('user'));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $store = [
            'name' => 'required|string|unique:users|min:6|max:150',
            'password' => 'nullable|string|min:3|max:50',
            'user_type' => 'required',
            'gender' => 'required|string',
            'phone' => ['sometimes', 'nullable', 'string', 'unique:users', new StartsWithProperPhoneCode],
            'phone2' => ['sometimes', 'nullable', 'string', 'unique:users', new StartsWithProperPhoneCode],
            'email' => 'sometimes|nullable|email|max:100|unique:users',
            'username' => 'sometimes|nullable|alpha_dash|min:8|max:100|unique:users',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:3|max:120',
            'state_id' => 'required',
            'lga_id' => 'required',
            'nal_id' => 'required',
            'work' => 'sometimes|required|string|max:150',
            'name2' => 'sometimes|required|string|min:6|max:150',
            'relation' => 'sometimes|required|string|min:4|max:150',
            'phone3' => ['sometimes', 'nullable', 'string', new StartsWithProperPhoneCode],
            'phone4' => ['sometimes', 'nullable', 'string', new StartsWithProperPhoneCode],
            'primary_id' => 'sometimes|nullable|string|unique:users|min:9|max:9',
            'secondary_id' => 'sometimes|nullable|string|unique:users|min:23|max:23',
            'file_number' => 'sometimes|nullable|string|max:15',
            'bank_acc_no' => 'sometimes|nullable|string|required_unless:bank_name,null|unique:staff_records|min:5|max:11',
            'bank_name' => 'sometimes|nullable|required_with:bank_acc_no',
            'tin_number' => 'sometimes|nullable|alpha_num|unique:staff_records|min:3|max:9',
            'ss_number' => 'sometimes|nullable|string|unique:staff_records|min:6|max:9',
            'licence_number' => 'sometimes|nullable|string|unique:staff_records|max:10',
            'place_of_living' => 'sometimes|nullable|string|max:100|min:3',
            'dob' => 'sometimes|nullable|date_format:' . Usr::getDateFormat(),
            // The regex allows for alphabetic characters and spaces within each subject (for example, "Literature in English" is valid). 
            // Each subject can be followed by a comma and optional spaces, before the next valid subject.
            'subjects_studied' => 'sometimes|nullable|max:250|regex:/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]+)*$/',
        ];

        // Rules that apply to the user making changes to other user's records
        $update = [
            'name' => 'required|string|min:6|max:150',
            'gender' => 'required|string',
            'phone' => ['sometimes', 'nullable', 'string', "unique:users,phone,{$this->user}", new StartsWithProperPhoneCode],
            'phone2' => ['sometimes', 'nullable', 'string', "unique:users,phone,{$this->user}", new StartsWithProperPhoneCode],
            'phone3' => ['sometimes', 'nullable', 'string', "unique:users,phone,{$this->user}", new StartsWithProperPhoneCode],
            'phone4' => ['sometimes', 'nullable', 'string', "unique:users,phone,{$this->user}", new StartsWithProperPhoneCode],
            'email' => "sometimes|nullable|email|max:100|unique:users,email,{$this->user}",
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:3|max:120',
            'state_id' => 'required',
            'lga_id' => 'required',
            'nal_id' => 'required',
            'work' => 'sometimes|required|string|max:150',
            'name2' => 'sometimes|required|string|min:6|max:150',
            'relation' => 'sometimes|required|string|min:4|max:150',
            'bank_name' => 'sometimes|nullable|required_with:bank_acc_no',
            'file_number' => 'sometimes|nullable|string|max:15',
            'primary_id' => ['sometimes', 'nullable', 'alpha_num', 'min:3', 'max:20', Rule::unique('users', 'primary_id')->ignore($this->getRouteUserIdParameter(), 'id')],
            'secondary_id' => ['sometimes', 'nullable', 'alpha_num', 'min:3', 'max:20', Rule::unique('users', 'secondary_id')->ignore($this->getRouteUserIdParameter(), 'id')],
            'bank_acc_no' => ['sometimes', 'nullable', 'string', 'min:5', 'max:11', 'required_unless:bank_name,null', Rule::unique('staff_records', 'bank_acc_no')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'tin_number' => ['sometimes', 'nullable', 'alpha_num', 'min:3', 'max:15', Rule::unique('staff_records', 'tin_number')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'ss_number' => ['sometimes', 'nullable', 'string', 'min:3', 'max:15', Rule::unique('staff_records', 'ss_number')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'licence_number' => ['sometimes', 'nullable', 'string', 'max:10', Rule::unique('staff_records', 'licence_number')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'place_of_living' => 'sometimes|nullable|string|max:100|min:3',
            'dob' => 'sometimes|nullable|date_format:' . Usr::getDateFormat(),
            'subjects_studied' => 'sometimes|nullable|max:250|regex:/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]+)*$/',
        ];

        return ($this->method() === 'POST') ? $store : $update;
    }

    public function attributes()
    {
        return [
            'nal_id' => 'nationality',
            'state_id' => 'state',
            'lga_id' => 'LGA',
            'phone2' => 'telephone',
            'name2' => 'close relative name',
            'phone3' => 'close relative phone',
            'phone4' => 'close relative mobile',
            'relation' => 'Parent\'s relation with the relative',
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

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $data_masked_attributes = ['phone', 'phone2', 'phone3', 'phone4', 'primary_id', 'secondary_id'];
        foreach ($data_masked_attributes as $attribute) {
            if (isset($input[$attribute])) {
                $input[$attribute] = rtrim($input[$attribute], "_");
            }
        }

        if ($this->method() === 'POST') {
            $input['user_type'] = Usr::decodeHash($input['user_type']);
        }

        if ($this->method() === 'PUT') {
            $this->user = Usr::decodeHash($this->user);
        }

        $this->getInputSource()->replace($input);
        return parent::getValidatorInstance();
    }
}

<?php

namespace App\Http\Requests;

use App\Helpers\Qs;
use Auth;
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
        if ($this->method() !== "POST")
            return Qs::decodeHash($this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $store =  [
            'name' => 'required|string|unique:users|min:6|max:150',
            'password' => 'nullable|string|min:3|max:50',
            'user_type' => 'required',
            'gender' => 'required|string',
            'phone' => 'sometimes|nullable|string|min:6|max:20',
            'phone2' => 'sometimes|nullable|string|min:6|max:20',
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
            'phone3' => 'sometimes|required|string|min:10|max:20',
            'phone4' => 'sometimes|nullable|string|min:10|max:20',
            'primary_id' => 'sometimes|nullable|string|unique:users|min:9|max:9',
            'secondary_id' => 'sometimes|nullable|string|unique:users|min:23|max:23',
            'file_number' => 'sometimes|nullable|string|max:15',
            'bank_acc_no' => 'sometimes|nullable|string|required_unless:bank_name,null|unique:staff_records|min:10|max:11',
            'bank_name' => 'sometimes|nullable|required_with:bank_acc_no',
            'tin_number' => 'sometimes|nullable|string|unique:staff_records|min:9|max:9',
            'ss_number' => 'sometimes|nullable|string|unique:staff_records|min:6|max:9',
            'licence_number' => 'sometimes|nullable|string|unique:staff_records|max:10',
            'place_of_living' => 'sometimes|nullable|string|max:100|min:3',
        ];
       
        // Rules that apply to the user making changes to other user's records
        $update =  [
            'name' => 'required|string|min:6|max:150',
            'gender' => 'required|string',
            'phone' => 'sometimes|nullable|string|min:6|max:20',
            'phone2' => 'sometimes|nullable|string|min:6|max:20',
            'email' => 'sometimes|nullable|email|max:100|unique:users,email,' . $this->user,
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:3|max:120',
            'state_id' => 'required',
            'lga_id' => 'required',
            'nal_id' => 'required',
            'work' => 'sometimes|required|string|max:150',
            'name2' => 'sometimes|required|string|min:6|max:150',
            'relation' => 'sometimes|required|string|min:4|max:150',
            'phone3' => 'sometimes|required|string|min:10|max:20',
            'phone4' => 'sometimes|nullable|string|min:10|max:20',
            'bank_name' => 'sometimes|nullable|required_with:bank_acc_no',
            'file_number' => 'sometimes|nullable|string|max:15',
            'primary_id' => ['sometimes', 'nullable', 'string', 'min:9', 'max:9', Rule::unique('users', 'primary_id')->ignore($this->getRouteUserIdParameter(), 'id')],
            'secondary_id' => ['sometimes', 'nullable', 'string', 'min:23', 'max:23', Rule::unique('users', 'secondary_id')->ignore($this->getRouteUserIdParameter(), 'id')],
            'bank_acc_no' => ['sometimes', 'nullable', 'string', 'min:10', 'max:11', 'required_unless:bank_name,null', Rule::unique('staff_records', 'bank_acc_no')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'tin_number' => ['sometimes', 'nullable', 'string', 'min:9', 'max:9', Rule::unique('staff_records', 'tin_number')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'ss_number' => ['sometimes', 'nullable', 'string', 'min:8', 'max:9', Rule::unique('staff_records', 'ss_number')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'licence_number' => ['sometimes', 'nullable', 'string', 'max:10', Rule::unique('staff_records', 'licence_number')->ignore($this->getRouteUserIdParameter(), 'user_id')],
            'place_of_living' => 'sometimes|nullable|string|max:100|min:3',
        ];

        return ($this->method() === 'POST') ? $store : $update;
    }

    public function attributes()
    {
        return  [
            'nal_id' => 'Nationality',
            'state_id' => 'State',
            'lga_id' => 'LGA',
            'user_type' => 'User',
            'phone2' => 'Telephone',
            'work' => 'Work/Job',
            'name2' => 'Close Relative Name',
            'phone3' => 'Phone',
            'phone4' => 'Telephone',
            'relation' => 'Parent\'s relation with the relative',
            'primary_id' => 'Primary ID card number',
            'secondary_id' => 'Secondary ID card number',
            'licence_number' => 'licence number',
            'file_number' => 'file number',
            'bank_acc_no' => 'bank account number',
            'tin_number' => 'tin number',
            'ss_number' => 'Social Security number',
        ];
    }

    protected function getValidatorInstance()
    {
        if ($this->method() === 'POST') {
            $input = $this->all();
            $input['user_type'] = Qs::decodeHash($input['user_type']);
            $this->getInputSource()->replace($input);
        }

        if ($this->method() === 'PUT') {
            $this->user = Qs::decodeHash($this->user);
        }

        return parent::getValidatorInstance();
    }
}

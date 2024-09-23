<?php

namespace App\Http\Requests;

use App\Helpers\Qs;
use App\Rules\Domain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function getRouteTenantIdParameter()
    {
        // If not data store
        if ($this->method() !== "POST")
            return Qs::decodeHash($this->route('tenant'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $store = [
            'domain' => ['required', 'bail', 'unique:domains', new Domain],
            'name' => 'required|string|unique:tenants,data->name',
            'remarks' => 'sometimes|nullable|max:300',
        ];

        $update = [
            'remarks' => 'sometimes|nullable|max:300',
            'domain' => ['required', Rule::unique('domains', 'domain')->ignore($this->getRouteTenantIdParameter(), 'tenant_id'), new Domain],
        ];

        return ($this->method() === 'POST') ? $store : $update;
    }
}

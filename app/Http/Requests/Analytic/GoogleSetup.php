<?php

namespace App\Http\Requests\Analytic;

use App\Helpers\Qs;
use App\Rules\Uppercase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoogleSetup extends FormRequest
{
    protected $property_id_type, $tag_id_type, $credential_file_attr, $credential_file_name;
    function __construct()
    {
        $this->property_id_type = 'google_analytic_property_id';
        $this->tag_id_type = 'google_analytic_tag_id';
        $this->credential_file_attr = 'service_account_credential_file';
        $this->credential_file_name = Qs::getSetting('google_analytic_service_account_credential_file');
    }

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
            $this->property_id_type => ['integer', 'nullable', Rule::requiredIf(
                $this->attributeDescriptionIsNull($this->property_id_type)
            )],
            $this->tag_id_type => ['nullable', 'starts_with:G-',  Rule::requiredIf(
                $this->attributeDescriptionIsNull($this->tag_id_type)
            ), new Uppercase],
            $this->credential_file_attr => ['file', 'mimes:json', 'max:2048', Rule::requiredIf(
                !$this->credentialFileExists($this->credential_file_name)
            )]
        ];
    }

    private function attributeDescriptionIsNull($attribute)
    {
        return Qs::getSetting($attribute) === NULL;
    }

    private function credentialFileExists($file_name)
    {
        $credential_file = storage_path('/app/public/' . Qs::getSetting('google_analytic_service_account_credential_file'));
        return file_exists($credential_file);
    }

    public function attributes()
    {
        return  [];
    }
}

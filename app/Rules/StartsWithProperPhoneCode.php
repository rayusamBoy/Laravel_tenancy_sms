<?php

namespace App\Rules;

use App\Models\Nationality;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Str;

class StartsWithProperPhoneCode implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Attempt to get the ID from the data (as usual for form request validation), 
        // or take one value for Excel validation, since the Excel package validates in batches 
        // and the value is the same for all rows.
        $nal_id = $this->data['nal_id'] ?? array_column($this->data, 'nal_id')[0];
        $phone_code = Nationality::where('id', $nal_id)->value('phone_code');
        $phone_code = "+$phone_code";

        if (!Str::startsWith($value, $phone_code))
            $fail("The :attribute must start with proper phone code: $phone_code.");
    }

    /**
     * All of the data under validation.
     * 
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}

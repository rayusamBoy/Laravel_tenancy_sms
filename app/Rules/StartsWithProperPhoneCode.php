<?php

namespace App\Rules;

use App\Models\Nationality;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StartsWithProperPhoneCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone_code_exists = Nationality::where('phone_code', substr($value, 1, 3))->exists();
        if (!$phone_code_exists)
            $fail('The :attribute country phone code is invalid.');
    }
}

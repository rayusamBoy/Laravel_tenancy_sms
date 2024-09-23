<?php

namespace App\Rules;

use Closure;
use App\Helpers\Qs;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsInTableNames implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, array_keys(Qs::getTableNames()))) {
            $fail('The :attribute field is invalid.');
        }
    }
}

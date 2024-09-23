<?php

namespace App\Rules;

use App\Helpers\Usr;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasStudentExamNumberPlaceholder implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!str_contains($value, Usr::getStudentExamNumberPlaceholder())) {
            $fail('The :attribute must contains the ' . Usr::getStudentExamNumberPlaceholder() . ' placeholder character.');
        }
    }
}

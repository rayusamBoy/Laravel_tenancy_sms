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
        $placeholder = Usr::getStudentExamNumberPlaceholder();
        if (!str_contains($value, $placeholder)) {
            $fail("The :attribute must contains the $placeholder placeholder character.");
        }
    }
}

<?php

namespace App\Rules;

use App\Helpers\Qs;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsInTableColsNames implements ValidationRule, DataAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validate the where field value
        $from = $this->data['from']; // Actual table name
        $special_cols = ['none', 'id'];  // Just for validation.

        if (Qs::isNotNull($value) && !in_array($value, array_merge(array_keys(Qs::getTableCols($from)), $special_cols))) {
            $fail('The :attribute field is invalid.');
        }
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

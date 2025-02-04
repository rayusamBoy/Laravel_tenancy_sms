<?php

namespace App\Rules;

use App\Helpers\Mk;
use App\Models\Exam;
use App\Models\MyClass;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasOrderedClassExamSession implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exams = Exam::get();
        $delimiter = Mk::getDelimiter();
        $class_names = MyClass::pluck('name')->toArray();
        $exam_names = $exams->pluck('name')->toArray();
        $exploded_value = explode($delimiter, $value);

        // Walk through the array values and covert them to lower case for efficient comparison
        array_walk($class_names, function (&$class) {
            $class = strtolower($class);
        });

        array_walk($exam_names, function (&$exam) {
            $exam = strtolower($exam);
        });

        if ((!isset($exploded_value[0]) || !isset($exploded_value[1]) || !isset($exploded_value[2]) || !isset($exploded_value[3])))
            $fail('The :attribute is invalid.');
        else {
            // The value to be compared to
            $class_received = strtolower(str_replace('_', ' ', $exploded_value[0] ?? null)); // Get class name from the filename
            $exam_received = strtolower(str_replace('_', ' ', $exploded_value[1] ?? null)); // Get exam name from the filename
            $year_received = "$exploded_value[2]-$exploded_value[3]"; // Get year from the filename
            // If any of the three data from the file as retrieved above do not exists in the database records return error.
            if (!in_array($class_received, $class_names) || !in_array($exam_received, $exam_names) || $year_received != Mk::getCurrentSession())
                $fail('The :attribute must be a valid file name.');
        }
    }
}

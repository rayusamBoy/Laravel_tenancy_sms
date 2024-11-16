<?php

namespace App\Rules;

use App\Helpers\Mk;
use App\Models\Exam;
use App\Models\MyClass;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasClassExamYearOnlyInOrder implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $delimiter = Mk::getDelimiter();
        $exams = Exam::get();

        $class_names = MyClass::pluck('name')->toArray();
        // Walk through the array values and covert them to lower case for efficient comparison
        array_walk($class_names, function (&$class) {
            $class = strtolower($class);
        });

        $exam_names = $exams->pluck('name')->toArray();
        array_walk($exam_names, function (&$exam) {
            $exam = strtolower($exam);
        });

        $exam_years = $exams->pluck('year')->toArray();
        $exploded = explode($delimiter, $value);

        if ((!isset($exploded[0]) || !isset($exploded[1]) || !isset($exploded[2]) || !isset($exploded[3])))
            $fail('The :attribute is invalid.');
        else {
            // The value to be compared to
            $class_received = strtolower(str_replace('_', ' ', $exploded[0] ?? null)); // Get class name from the filename
            $exam_received = strtolower(str_replace('_', ' ', $exploded[1] ?? null)); // Get exam name from the filename
            $year_received = $exploded[2] ?? null . '-' . $exploded[3] ?? null; // Get year from the filename
            // If any of the three data from the file as retrieved above do not exists in the database records return error.
            if (!in_array($class_received, $class_names) && !in_array($exam_received, $exam_names) && !in_array($year_received, $exam_years))
                $fail('The :attribute must be a valid file name.');
        }
    }
}

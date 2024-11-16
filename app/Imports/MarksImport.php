<?php

namespace App\Imports;

use App\Helpers\Mk;
use App\Models\SubjectRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MarksImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $year, $class_id, $exam_id, $session, $subjects, $st_mark, $marks_table;

    public function __construct($year, int $class_id, int $exam_id, $st_mark)
    {
        $this->year = $year;
        $this->class_id = $class_id;
        $this->exam_id = $exam_id;
        $this->session =  Mk::getSetting('current_session');
        $this->st_mark = $st_mark;
        $this->marks_table = 'marks';
    }

    public function collection(Collection $rows)
    {
        $subjects = Mk::getSubjects($this->class_id);
        foreach ($rows as $row) {
            $s_data = $this->st_mark->where('user.username', $row['admission_number'])->first(); // Get student record by student admission number
            $sid = $s_data->user->id;
            $sec_id = $s_data->section_id;

            foreach ($subjects as $sub) {
                // Get students ids. For the subject with only specific students who study it 
                $students_ids = SubjectRecord::where(['subject_id' => $sub->id])->where('students_ids', '!=', NULL)->value('students_ids');;

                if ($students_ids != NULL) {
                    $students_ids = unserialize($students_ids); // Get students ids
                    if (in_array($sid, $students_ids)) {
                        // If the subject record has students ids, and current student id is in there, update
                        $this->updateMark($row, $sid, $sec_id, $sub->id, $sub->name);
                    } else {
                        // Delete any mark that belong to student not taking the particular subject 
                        // Useful if there was a mistake during subject assignment
                        $this->deleteMark($sid, $sec_id, $sub->id);
                    }
                } else {
                    $this->updateMark($row, $sid, $sec_id, $sub->id, $sub->name);
                }
            }
        }
    }

    public function updateMark($row, $sid, $sec_id, $sub_id, $sub_name)
    {
        $where = [
            'student_id' => $sid,
            'my_class_id' => $this->class_id,
            'section_id' => $sec_id,
            'exam_id' => $this->exam_id,
            'year' => $this->year,
            'subject_id' => $sub_id,
        ];

        $data = ['exm' => $row[$this->replaceSpaceWithUnderscore($sub_name)]];

        return DB::table($this->marks_table)->where($where)->update($data);
    }

    public function deleteMark($sid, $sec_id, $sub_id)
    {
        $where = [
            'student_id' => $sid,
            'my_class_id' => $this->class_id,
            'section_id' => $sec_id,
            'exam_id' => $this->exam_id,
            'year' => $this->year,
            'subject_id' => $sub_id,
        ];

        return DB::table($this->marks_table)->where($where)->delete();
    }

    public function replaceSpaceWithUnderscore($string)
    {
        /**
         * This excel package it automatically replace the subject names consiting of words separated by a space with an underscore in small case.
         * Fore example if the subject in the file appear as 'English Language' it will be converted to 'english_language'.
         * But, the subject names in the database are similar to the ones exported to the file (the same file to be imported),
         * therefore to match the case this function will convert those subjects names from the database to the form the package requires/uses.
         */
        return str_replace(' ', '_', strtolower($string));
    }

    public function replaceUnderserscoreWithSpace($string)
    {
        return str_replace('_', ' ', strtolower($string));
    }

    public function customValidationAttributes()
    {
        $attributes = [];
        $admission_no_attribute = [
            'admission_number' => 'admission number'
        ];
        // Associate the altered subject name (the one with replaced space with underscore) with the actual name (the one with the space) in the file.
        foreach (Mk::getSubjects($this->class_id) as $sub) {
            $attr = [
                $this->replaceSpaceWithUnderscore($sub->name) => $this->replaceUnderserscoreWithSpace($sub->name),
            ];
            $attributes = array_merge($attr, $attributes);
        }
        $attributes = array_merge($attributes, $admission_no_attribute);
        
        return $attributes;
    }

    public function rules(): array
    {
        $sub_rules = [];
        // Validation rule for marks
        foreach (Mk::getSubjects($this->class_id) as $sub) {
            $rule = [
                '*.' . $this->replaceSpaceWithUnderscore($sub->name) => ['min:0', 'max:100', 'integer', 'nullable', 'sometimes'],
            ];
            $sub_rules = array_merge($rule, $sub_rules); // Merge sub array rule to main array rules per each iteration.
        }

        // Validation rules for students admission number
        $collection = array_merge(
            [
                '*.admission_number' => ['exists:users,username', 'string', 'required']
            ],
            $sub_rules
        );

        return $collection;
    }

    public function headingRow(): int
    {
        return 4;
    }
}

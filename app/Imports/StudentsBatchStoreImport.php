<?php

namespace App\Imports;

use App\Helpers\Usr;
use App\Http\Controllers\SupportTeam\StudentRecordController;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Repositories\DormRepo;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsBatchStoreImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, WithCustomValueBinder
{
    protected $location, $dorm, $my_class, $session, $user_parameters, $student_parameters, $student_record_request, $parameters_columns_to_hide_and_populate, $user, $student;
    public function __construct()
    {
        $this->session = Usr::getCurrentSession();
        $this->my_class = new MyClassRepo();
        $this->user = new UserRepo();
        $this->student = new StudentRepo();
        $this->location = new LocationRepo();
        $this->dorm = new DormRepo();
        $this->student_record_request = new StudentRecordCreate();
        $this->user_parameters = StudentRecordController::getUserHeadings(false);
        $this->student_parameters = StudentRecordController::getStudentHeadings(false);
        $this->parameters_columns_to_hide_and_populate = ['state_id', 'nal_id', 'my_class_id'];
    }

    public function bindValue(Cell $cell, $value)
    {
        $date_columns = [];

        $date_parameters = array_keys(array_filter($this->student_record_request->rules(), function ($rule) {
            return is_array($rule) ? in_array(['date', 'date_format'], $rule) : str_contains($rule, 'date');
        }));

        foreach ($date_parameters as $date_parameter) {
            $date_columns[] = $this->mapHeadingToLetter($date_parameter);
        }

        // If the value is empty and is a part of date column convert the excel serialized date to php date
        if (!empty($value) && in_array($cell->getColumn(), $date_columns)) {
            $cell->setValueExplicit(Date::excelToDateTimeObject($value)->format(Usr::getDateFormat()), DataType::TYPE_STRING);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function isEmptyWhen(array $row)
    {
        $when = false;
        $attributes = array_merge($this->user_parameters, $this->student_parameters);

        foreach ($attributes as $name => $attribute) {
            // Skip user representative heading row; Just we will check the first column if it contains the name.
            // Some heading names have * at the end; that's why we use str_contains instead of == or ===.
            // Order matter; this will always counted as the first data row, so we will check it first before all
            $when = str_contains($row[$attribute], $name);
            break;
        }
        // Skip all rows that has only hidden populated parameters; Let's check if are the only values that exist
        $non_null_row_values = array_keys(array_filter($row, function ($value) {
            return $value != NULL;
        }));
        // If the condition is true already, just return it. Else if the difference is 0; that means the arrays are the same
        return $when ?: count(array_diff($non_null_row_values, $this->parameters_columns_to_hide_and_populate)) === 0;
    }

    public function collection(Collection $rows)
    {
        $data = $sr = [];

        $my_class_id = array_column($rows->toArray(), 'my_class_id')[0];
        $state_id = array_column($rows->toArray(), 'state_id')[0];

        $blood_groups = Usr::getBloodGroups();
        $lgas = $this->location->getLgas($state_id);
        $sections = $this->my_class->getClassSections($my_class_id);
        $dorms = $this->dorm->getAll();

        foreach ($rows as $row) {
            $row['bg_id'] = $blood_groups->where('name', $row['bg_id'])->value('id');
            $row['lga_id'] = $lgas->where('name', $row['lga_id'])->value('id');
            $row['section_id'] = $sections->where('name', $row['section_id'])->value('id');
            $row['dorm_id'] = $dorms->where('name', $row['dorm_id'])->value('id');

            foreach ($this->user_parameters as $param) {
                $data[$param] = $row[$param];
            }

            $data['user_type'] = $user_type = 'student';
            $data['name'] = $name = strtoupper($row['name']);
            $data['code'] = $code = strtoupper(Str::random(10));
            $data['password'] = Hash::make('student');
            $data['photo'] = Usr::createAvatar($name, $code, $user_type);

            $adm_no = $row['adm_no'];
            $ct = $this->my_class->findTypeByClass($row['my_class_id'])->code;
            $data['username'] = strtoupper(Usr::getAppCode() . '/' . $ct . '/' . date('Y', strtotime($row['date_admitted'])) . '/' . ($adm_no ?: mt_rand(1000, 99999)));

            $user = $this->user->create($data);

            foreach ($this->student_parameters as $param) {
                $sr[$param] = $row[$param];
            }

            $sr['user_id'] = $user->id;
            $sr['adm_no'] = $user->username;
            $sr['session'] = $this->session;
            // Format string according to the enforced rule
            $sr['house_no'] = strtoupper($sr['house_no']);
            $sr['ps_name'] = ucfirst($sr['ps_name']);
            $sr['ss_name'] = ucfirst($sr['ss_name']);
            $sr['p_status'] = ucfirst($sr['p_status']);

            $this->student->createRecord($sr);
        }
    }

    public function customValidationAttributes()
    {
        $attributes = array_merge($this->user_parameters, $this->student_parameters);
        return array_flip($attributes);
    }

    public function rules(): array
    {
        return $this->student_record_request->rules();
    }

    public function headingRow(): int
    {
        // Hidden row; visible heading is row 5
        return 4;
    }

    private function mapHeadingToLetter($heading_param)
    {
        $all_headings = array_values(array_merge($this->user_parameters, $this->student_parameters));
        return Usr::numberToLetter(array_search($heading_param, $all_headings) + 1);
    }
}

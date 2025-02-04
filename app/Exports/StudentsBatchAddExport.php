<?php

namespace App\Exports;

use App\Helpers\Usr;
use App\Http\Controllers\SupportTeam\StudentRecordController;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Repositories\DormRepo;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsBatchAddExport implements FromView, ShouldAutoSize, WithStyles, WithProperties, WithEvents, WithColumnWidths
{
    protected $data, $location, $my_class, $dorm, $all_headings, $student_record_request, $parameters_columns_to_hide_and_populate, $end_data_row;

    public function __construct($data)
    {
        $this->data = $data;
        $this->location = new LocationRepo();
        $this->my_class = new MyClassRepo();
        $this->dorm = new DormRepo();
        $this->student_record_request = new StudentRecordCreate();
        $user_headings = StudentRecordController::getUserHeadings();
        $student_headings = StudentRecordController::getStudentHeadings();
        $this->all_headings = array_merge($user_headings, $student_headings);
        $this->parameters_columns_to_hide_and_populate = ['state_id', 'nal_id', 'my_class_id'];
        $this->end_data_row = 1000;
    }

    public function columnWidths(): array
    {
        $column_widths = [];
        foreach ($this->all_headings as $heading) {
            if (in_array(strtolower($heading), ['name', 'email', 'address', 'food_taboos', 'chp'])) {
                $column_widths[$this->mapHeadingToLetter($heading)] = match ($heading) {
                    'name' => 35,
                    'email' => 25,
                    'address' => 25,
                    'food_taboos' => 45,
                    'chp' => 45,
                };
            }
        }

        return $column_widths;
    }

    public function properties(): array
    {
        return [
            'creator' => Usr::getSystemName(),
            'lastModifiedBy' => Usr::getSystemName(),
            'title' => 'Students Add Export',
            'description' => 'Excel template for adding students',
            'subject' => 'Students Add Export',
            'keywords' => 'students,export,spreadsheet',
            'category' => 'Students',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $number_of_heading_rows = 5;
        $start_data_row = $number_of_heading_rows + 1;

        $headings_names_count = count($this->data['heading_names']);
        $end_col_index = $headings_names_count;
        $start_data_col = 1;

        $headings_list_to_validate = ['gender', 'bg_id', 'lga_id', 'religion', 'section_id', 'disability', 'p_status', 'dorm_id'];

        $needs_dropdown_list_validation = [];

        foreach ($headings_list_to_validate as $heading) {
            $needs_dropdown_list_validation[$heading] = array_search($heading, $this->all_headings) + 1; // +1 because Excel columns start from 1
        }

        foreach ($needs_dropdown_list_validation as $name => $its_validation_col) {
            switch ($name) {
                case "gender":
                    $dropdown_options = Usr::getGenders();
                    $validation_col = $its_validation_col;
                    break;
                case "bg_id":
                    $dropdown_options = Usr::getBloodGroups()->pluck('name')->toArray();
                    $validation_col = $its_validation_col;
                    break;
                case "lga_id":
                    $dropdown_options = $this->location->getLgas($this->data['state']->id)->pluck('name')->toArray();
                    $validation_col = $its_validation_col;
                    break;
                case "religion":
                    $dropdown_options = Usr::getReligions();
                    $validation_col = $its_validation_col;
                    break;
                case "section_id":
                    $dropdown_options = $this->my_class->getClassSections($this->data['class']->id)->pluck('name')->toArray();
                    $validation_col = $its_validation_col;
                    break;
                case "disability":
                    $dropdown_options = array_values(Usr::getDisabilities());
                    $validation_col = $its_validation_col;
                    break;
                case "p_status":
                    $dropdown_options = Usr::getStudentParentsStatus();
                    $validation_col = $its_validation_col;
                    break;
                case "dorm_id":
                    $dropdown_options = $this->dorm->getAll()->pluck('name')->toArray();
                    $validation_col = $its_validation_col;
                    break;
            }

            $type_list_validation = $sheet->getParent()->getActiveSheet()->getCell([$its_validation_col, $start_data_row + 1])->getDataValidation();
            $type_list_validation->setType(DataValidation::TYPE_LIST);
            $type_list_validation->setErrorStyle(DataValidation::STYLE_STOP);
            $type_list_validation->setAllowBlank(false);
            $type_list_validation->setShowInputMessage(true);
            $type_list_validation->setShowErrorMessage(true);
            $type_list_validation->setShowDropDown(true);
            $type_list_validation->setErrorTitle('Input error');
            $type_list_validation->setError('Value is not in list.');
            $type_list_validation->setPromptTitle('Select from list.');
            $type_list_validation->setPrompt('Please, select a value from the drop-down list.');
            $type_list_validation->setFormula1(sprintf('"%s"', implode(',', $dropdown_options)));

            for ($col = $validation_col; $col <= $validation_col; $col++) {
                for ($row = $start_data_row; $row <= $this->end_data_row + $number_of_heading_rows; $row++) {
                    $sheet->getParent()->getActiveSheet()->getCell([$col, $row])->setDataValidation(clone $type_list_validation);
                }
            }

            $dropdown_options = []; // Clear any looped data in memory
        }

        // Enable worksheet protection
        $sheet->getParent()->getActiveSheet()->getProtection()->setSheet(true);

        // Set password
        $sheet->getProtection()->setPassword('AllahKnowsBest');

        // Lock all cells then unlock the specified cell
        $sheet->getParent()->getActiveSheet()
            ->getStyle([$start_data_col, $start_data_row, $end_col_index, $this->end_data_row + $number_of_heading_rows])
            ->getProtection()
            ->setLocked(Protection::PROTECTION_UNPROTECTED);

        // Set bg color and font color for heading data
        $headers_arr_range = [1, 1, $end_col_index, $number_of_heading_rows];
        $sheet->getStyle($headers_arr_range)->getFill()->applyFromArray(['fillType' => 'solid', 'color' => ['rgb' => '171717']]);
        $sheet->getStyle($headers_arr_range)->getFont()->applyFromArray(['color' => ['rgb' => 'FFFFFF']]);
    }

    public function registerEvents(): array
    {
        $parameters_columns_to_hide_and_populate = $this->parameters_columns_to_hide_and_populate;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($parameters_columns_to_hide_and_populate) {
                // Hide the 4th parameter row
                $event->sheet->getDelegate()->getRowDimension(4)->setVisible(false);
                foreach ($parameters_columns_to_hide_and_populate as $parameter) {
                    $column = $this->mapHeadingToLetter($parameter);
                    $event->sheet->getDelegate()->getColumnDimension($column)->setVisible(false);
                }
            },
        ];
    }

    public function view(): View
    {
        $this->data['parameters_with_rule_required'] = array_keys(array_filter($this->student_record_request->rules(), function ($rule) {
            return is_array($rule) ? in_array('required', $rule) : str_contains($rule, 'required');
        }));
        $this->data['parameters_columns_to_hide_and_populate'] = $this->parameters_columns_to_hide_and_populate;
        $this->data['end_data_row'] = $this->end_data_row;
        return view('pages.support_team.students.batch.table', $this->data);
    }

    private function mapHeadingToLetter($heading_param)
    {
        return Usr::numberToLetter(array_search($heading_param, $this->all_headings) + 1);
    }
}

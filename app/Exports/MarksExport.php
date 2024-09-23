<?php

namespace App\Exports;

use App\Helpers\Mk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithProperties;

class MarksExport implements FromView, ShouldAutoSize, WithStyles, WithProperties
{
    protected $exam, $class, $year, $marks, $class_type_id;

    public function __construct($exam, $class, $marks, $class_type_id)
    {
        $this->exam = $exam;
        $this->class = $class;
        $this->marks = $marks;
        $this->class_type_id = $class_type_id;
        $this->year =  Mk::getSetting('current_session');
    }

    public function properties(): array
    {
        return [
            'creator'        => Mk::getSystemName(),
            'lastModifiedBy' => Mk::getSystemName(),
            'title'          => 'Marks Export',
            'description'    => 'Excel marks export template for students marks',
            'subject'        => 'Marks',
            'keywords'       => 'marks,export,spreadsheet',
            'category'       => 'Marks',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $advance_level_id = 3;
        $advance_level_number_of_initial_cols = 4; // for; S/N, ADM NO., NAME, and COMB
        $ordinary_level_number_of_initial_cols = 3; // for; S/N, ADM NO., and NAME
        $titles_and_subject_names_initial_rows = 4;
        $start_data_row = 5;

        $sub_count = Mk::getSubjects($this->class->id)->count();
        $end_col_index = ($this->class_type_id == $advance_level_id) ? $sub_count + $advance_level_number_of_initial_cols : $sub_count + $ordinary_level_number_of_initial_cols;
        $start_data_col = $col_index = ($this->class_type_id == $advance_level_id) ? $advance_level_number_of_initial_cols + 1 : $ordinary_level_number_of_initial_cols + 1; // The column the data will be populated from
        $end_data_row = $this->marks->unique('user.id')->whereNotNull('user')->count() + $titles_and_subject_names_initial_rows; // Plus the first four rows

        $validation = $sheet->getParent()->getActiveSheet()->getCell([$col_index, 5])->getDataValidation();
        $validation->setType(DataValidation::TYPE_DECIMAL);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('The input is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt('Numbers only between 0 and 100.');
        $validation->setFormula1(0);
        $validation->setFormula2(100);

        for ($col = $start_data_col; $col <= $end_col_index; $col++) {
            for ($row = $start_data_row; $row <= $end_data_row; $row++) {
                $sheet->getParent()->getActiveSheet()->getCell([$col, $row])->setDataValidation(clone $validation);
            }
        }

        // Enable worksheet protection
        $sheet->getParent()->getActiveSheet()->getProtection()->setSheet(true);

        // Set password
        $sheet->getProtection()->setPassword('AllahKnowsBest');

        // Lock all cells then unlock the specified cell
        $sheet->getParent()->getActiveSheet()
            ->getStyle([$start_data_col, $start_data_row, $end_col_index, $end_data_row])
            ->getProtection()
            ->setLocked(Protection::PROTECTION_UNPROTECTED);

        // Set bg color and font color for heading data
        $headers_arr_range = [1, 1, $end_col_index, $titles_and_subject_names_initial_rows];
        $sheet->getStyle($headers_arr_range)->getFill()->applyFromArray(['fillType' => 'solid', 'color' => ['rgb' => '2b2b2b']]);
        $sheet->getStyle($headers_arr_range)->getFont()->applyFromArray(['color' => ['rgb' => 'FFFFFF']]);

        // Set bg color and font color for side protected data
        //$side_data_arr_range = [1, $start_data_row, $end_col_index - $sub_count, $end_data_row];
        //$sheet->getStyle($side_data_arr_range)->getFill()->applyFromArray(['fillType' => 'solid', 'color' => ['rgb' => '263238']]);
        //$sheet->getStyle($side_data_arr_range)->getFont()->applyFromArray(['color' => ['rgb' => 'FFFFFF']]);
    }

    public function view(): View
    {
        $d['marks'] = $marks = $this->marks;
        $d['subjects'] = Mk::getSubjects($this->class->id);
        $d['m'] =  $marks->first();
        $d['class_type_id'] = $this->class_type_id;

        return view('pages.support_team.marks.batch.table', $d);
    }
}

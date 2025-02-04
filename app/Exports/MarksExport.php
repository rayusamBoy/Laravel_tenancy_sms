<?php

namespace App\Exports;

use App\Helpers\Mk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarksExport implements FromView, ShouldAutoSize, WithStyles, WithProperties
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function properties(): array
    {
        return [
            'creator' => Mk::getSystemName(),
            'lastModifiedBy' => Mk::getSystemName(),
            'title' => 'Marks Export',
            'description' => 'Excel marks export template for students marks',
            'subject' => 'Marks',
            'keywords' => 'marks,export,spreadsheet',
            'category' => 'Marks',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $number_of_initial_cols = 4; // 4 - S/N, ADMISSION NUMBER, FULL NAME, and SECTION
        $titles_and_subject_names_initial_rows = 4;
        $start_data_row = $titles_and_subject_names_initial_rows + 1;

        $sub_count = Mk::getSubjects($this->data['class']->id)->count();
        $end_col_index = $sub_count + $number_of_initial_cols;
        $start_data_col = $col_index = $number_of_initial_cols + 1; // The column the data will be populated from
        $end_data_row = $this->data['marks']->unique('user.id')->whereNotNull('user')->count() + $titles_and_subject_names_initial_rows; // Plus the first four rows

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
        $sheet->getStyle($headers_arr_range)->getFill()->applyFromArray(['fillType' => 'solid', 'color' => ['rgb' => '171717']]);
        $sheet->getStyle($headers_arr_range)->getFont()->applyFromArray(['color' => ['rgb' => 'FFFFFF']]);
    }

    public function view(): View
    {
        return view('pages.support_team.marks.batch.table', $this->data);
    }
}

<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamAnnounce extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'exam_id' => 'required|integer',
            'message' => 'required|string|max:100',
            'duration' => 'required|integer|max:' . time() + 604800, // Limit duration to seven days (same as 604800 seconds) from the time of creation.
        ];
    }
}

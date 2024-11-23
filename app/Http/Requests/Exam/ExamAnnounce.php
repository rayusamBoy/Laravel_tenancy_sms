<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamAnnounce extends FormRequest
{
    protected $max_duration_in_secs, $min_duration_in_secs;
    public function __construct()
    {
        $seconds_to_add = 604800; // 7 days
        $this->max_duration_in_secs = time() + $seconds_to_add;
        $this->min_duration_in_secs = 86400; // 1 day
    }

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
            'exam_id' => 'required|integer|unique:exam_announces',
            'message' => 'required|string|max:100',
            'duration' => "required|integer|max:{$this->max_duration_in_secs}|min:{$this->min_duration_in_secs}",
        ];
    }

    public function attributes()
    {
        return [
            'exam_id' => 'exam announce',
        ];
    }

    public function messages()
    {
        return [
            'duration.max' => "The duration may not be greater than {$this->max_duration_in_secs} seconds (7 days).",
            'duration.min' => "The duration must be at least {$this->min_duration_in_secs} seconds (1 day).",
        ];
    }
}

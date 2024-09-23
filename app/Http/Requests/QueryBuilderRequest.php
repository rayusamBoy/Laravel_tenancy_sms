<?php

namespace App\Http\Requests;

use App\Rules\ExistsInTableNames;
use App\Rules\ExistsInTableColsNames;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class QueryBuilderRequest extends FormRequest
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
            'select' => 'required|array',
            'from' => ['required', 'string', new ExistsInTableNames],
            'where' => ['sometimes', 'string', new ExistsInTableColsNames],
            'where_two' => ['sometimes', 'string', new ExistsInTableColsNames],
            'condition' => 'required_unless:where,none|string',
            'condition_two' => 'required_unless:where_two,none|string',
            'input' => 'required_unless:where,none|string',
            'input_two' => 'required_unless:where_two,none|string',
            'limit' => ['nullable', 'integer', 'min:0', 'max:' . DB::table($this->input("from"))->count()],
            'orderby_column' => ['required', 'string', new ExistsInTableColsNames],
            'orderby_direction' => 'required|string|in:asc,desc',
        ];
    }
}

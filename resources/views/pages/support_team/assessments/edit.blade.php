<form class="ajax-update" action="{{ route('assessments.update', [$exam_id, $my_class_id, $subject_id, $section_id ?? null]) }}" method="post">
    @csrf @method('put')
    <table class="table table-striped data-table assessments float-head">
        <thead>
            <tr class="hide">
                <th class="pr-0" colspan="2">
                    Last Updated: {{ $asmnt_records->first()->updated_at->diffForHumans() }}
                </th>
                <th class="text-center">ClASSWORK ({{ $exam->cw_denominator }})</th>
                <th class="text-center">HOME WORK ({{ $exam->hw_denominator }})</th>
                <th class="text-center">TOPIC TEST ({{ $exam->tt_denominator }})</th>
                <th class="text-center">TERMED TEST ({{ $exam->tdt_denominator }})</th>
            </tr>

            <tr class="hide">
                <th colspan="2"></th>
                <th>
                    <table class="table-layout-fixed">
                        <tr class="text-left classwork-th">
                            <th class="w-1pcnt">1</th>
                            <th class="w-1pcnt">2</th>
                            <th class="w-1pcnt">3</th>
                            <th class="w-1pcnt">4</th>
                            <th class="w-1pcnt">5</th>
                            <th class="w-1pcnt">6</th>
                            <th class="w-1pcnt">7</th>
                            <th class="w-1pcnt">8</th>
                            <th class="w-1pcnt">9</th>
                            <th class="w-1pcnt">10</th>
                        </tr>
                    </table>
                </th>
                <th>
                    <table class="w-100 table-layout-fixed">
                        <tr class="homework-th">
                            <th class="w-1pcnt">1</th>
                            <th class="w-1pcnt">2</th>
                            <th class="w-1pcnt">3</th>
                            <th class="w-1pcnt">4</th>
                            <th class="w-1pcnt">5</th>
                        </tr>
                    </table>
                </th>
                <th>
                    <table class="table-layout-fixed">
                        <tr class="classwork-th">
                            <th class="w-1pcnt">1</th>
                            <th class="w-1pcnt">2</th>
                            <th class="w-1pcnt">3</th>
                        </tr>
                    </table>
                </th>
                <th>
                    <table>
                        <tr class="classwork-th">
                            <th>1</th>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="text-center">S/N</th>
                <th>NAME</th>
                <th class="text-center" colspan="4">STUDENT'S MARKS</th>
            </tr>
        </thead>

        <tbody class="ca-tbody">
            @foreach($asmnt_records->sortBy('user.name') as $asmnt)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $asmnt->user->name }} </td>
                {{-- Classwork --}}
                <td>
                    <table class="data-table text-center">
                        <tr>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="1st Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][1]" value="{{ $asmnt->cw1 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="2nd Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][2]" value="{{ $asmnt->cw2 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="3rd Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][3]" value="{{ $asmnt->cw3 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="4th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][4]" value="{{ $asmnt->cw4 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="5th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][5]" value="{{ $asmnt->cw5 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="6th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][6]" value="{{ $asmnt->cw6 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="7th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][7]" value="{{ $asmnt->cw7 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="8th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][8]" value="{{ $asmnt->cw8 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="9th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][9]" value="{{ $asmnt->cw9 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="10th Classwork ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="cw_[{{ $asmnt->id }}][10]" value="{{ $asmnt->cw10 }}" type="number"></td>
                        </tr>
                    </table>
                </td>
                {{-- Homework --}}
                <td>
                    <table class="data-table text-center">
                        <tr>
                            <td class="p-0"><input data-html="true" data-toggle="tooltip" title="1st Homework ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="hw_[{{ $asmnt->id }}][1]" value="{{ $asmnt->hw1 }}" type="number"></td>
                            <td class="p-0"><input data-html="true" data-toggle="tooltip" title="2nd Homework ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="hw_[{{ $asmnt->id }}][2]" value="{{ $asmnt->hw2 }}" type="number"></td>
                            <td class="p-0"><input data-html="true" data-toggle="tooltip" title="3rd Homework ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="hw_[{{ $asmnt->id }}][3]" value="{{ $asmnt->hw3 }}" type="number"></td>
                            <td class="p-0"><input data-html="true" data-toggle="tooltip" title="4th Homework ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="hw_[{{ $asmnt->id }}][4]" value="{{ $asmnt->hw4 }}" type="number"></td>
                        </tr>
                    </table>
                </td>
                {{-- Topic test --}}
                <td>
                    <table class="data-table text-center">
                        <tr>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="1st Topic Test ({{ $exam->tt_denominator }})" min="0" max="{{ $exam->tt_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="tt_[{{ $asmnt->id }}][1]" value="{{ $asmnt->tt1 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="2nd Topic Test ({{ $exam->tt_denominator }})" min="0" max="{{ $exam->tt_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="tt_[{{ $asmnt->id }}][2]" value="{{ $asmnt->tt2 }}" type="number"></td>
                            <td class="p-0"><input @readonly($is_restricted) data-html="true" data-toggle="tooltip" title="3rd Topic Test ({{ $exam->tt_denominator }})" min="0" max="{{ $exam->tt_denominator }}" class="{{ $is_restricted ? 'text-center cursor-not-allowed' : 'text-center' }}" name="tt_[{{ $asmnt->id }}][3]" value="{{ $asmnt->tt3 }}" type="number"></td>
                        </tr>
                    </table>
                </td>
                {{-- Exam --}}
                <td class="termed-test-data"><input readonly="readonly" title="TERMED TEST ({{ $exam->tdt_denominator }})" min="0" max="{{ $exam->tdt_denominator }}" class="text-center cursor-not-allowed" name="exm_{{ $asmnt->id }}" value="{{ $asmnt->exm }}" type="number"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Update Button --}}
    <div class="text-center mt-2">
        <button type="submit" class="btn btn-sm btn-primary" id="update">Update Assessments <i class="material-symbols-rounded ml-2">send</i></button>
    </div>

    {{-- Print Button --}}
    <div class="text-center mt-2">
        <a target="_blank" href="{{ route('assessments.print_assessments', [$exam_id, $my_class_id, $subject_id, $year, $section_id ?? null]) }}" class="btn btn-sm btn-danger"><i class="material-symbols-rounded mr-2">print</i> Print Assessment Records</a>
    </div>
</form>

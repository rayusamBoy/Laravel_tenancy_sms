<form class="ajax-update" action="{{ route('assessments.update', [$exam_id, $my_class_id, $subject_id, $section_id ?? null]) }}" method="post">
    @csrf @method('put')
    <table class="table table-striped data-table assessments">
        <thead>
            <tr class="hide">
                <th class="ps-1" rowspan="2" colspan="2">
                    <ul class="text-center pb-0 pt-0 list-unstyled" style="padding-left: 5px; padding-right: 1px;">
                        <li>Created: </li>
                        <li class="font-italic">{{ $created_at }}</li>
                        <li>Updated: </li>
                        <li class="font-italic">{{ $updated_at }}</li>
                    </ul>
                </th>
                <th class="text-center">ClASSWORK ({{ $exam->cw_denominator }})</th>
                <th class="text-center">HOME WORK ({{ $exam->hw_denominator }})</th>
                <th class="text-center">TOPIC TEST ({{ $exam->tt_denominator }})</th>
                <th class="text-center">TERMED TEST ({{ $exam->tdt_denominator }})</th>
            </tr>
            <tr class="hide" >
                <td>
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
                </td>
                <td>
                    <table class="w-100 table-layout-fixed">
                        <tr class="homework-th">
                            <th class="w-1pcnt">1</th>
                            <th class="w-1pcnt">2</th>
                            <th class="w-1pcnt">3</th>
                            <th class="w-1pcnt">4</th>
                            <th class="w-1pcnt">5</th>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table-layout-fixed">
                        <tr class="classwork-th">
                            <th class="w-1pcnt">1</th>
                            <th class="w-1pcnt">2</th>
                            <th class="w-1pcnt">3</th>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr class="classwork-th">
                            <th>1</th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="text-center">
                <th>S/N</th>
                <th>NAME</th>
                <th colspan="4">STUDENT'S MARKS</th>
            </tr>
        </thead>
        <tbody class="ca-tbody">
            @foreach($asmnt_records->whereNotNull('user')->sortBy('user.name') as $asmnt)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $asmnt->user->name }} </td>
                {{-- Class work --}}
                <td>
                    <table class="data-table">
                        <tr>
                            <td class="p-0"><input title="1st Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 1 }}]" value="{{ $asmnt->cw1 }}" type="number"></td>
                            <td class="p-0"><input title="2nd Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 2 }}]" value="{{ $asmnt->cw2 }}" type="number"></td>
                            <td class="p-0"><input title="3rd Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 3 }}]" value="{{ $asmnt->cw3 }}" type="number"></td>
                            <td class="p-0"><input title="4th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 4 }}]" value="{{ $asmnt->cw4 }}" type="number"></td>
                            <td class="p-0"><input title="5th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 5 }}]" value="{{ $asmnt->cw5 }}" type="number"></td>
                            <td class="p-0"><input title="6th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 6 }}]" value="{{ $asmnt->cw6 }}" type="number"></td>
                            <td class="p-0"><input title="7th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 7 }}]" value="{{ $asmnt->cw7 }}" type="number"></td>
                            <td class="p-0"><input title="8th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 8 }}]" value="{{ $asmnt->cw8 }}" type="number"></td>
                            <td class="p-0"><input title="9th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 9 }}]" value="{{ $asmnt->cw9 }}" type="number"></td>
                            <td class="p-0"><input title="10th Class Work ({{ $exam->cw_denominator }})" min="0" max="{{ $exam->cw_denominator }}" class="text-center" name="cw_[{{ $asmnt->id + 10 }}]" value="{{ $asmnt->cw10 }}" type="number"></td>
                        </tr>
                    </table>
                </td>
                {{-- Home work --}}
                <td>
                    <table class="data-table">
                        <tr>
                            <td class="p-0"><input title="1st Home Work ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="text-center" name="hw_[{{ $asmnt->id + 1}}]" value="{{ $asmnt->hw1 }}" type="number"></td>
                            <td class="p-0"><input title="2nd Home Work ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="text-center" name="hw_[{{ $asmnt->id + 2}}]" value="{{ $asmnt->hw2 }}" type="number"></td>
                            <td class="p-0"><input title="3rd Home Work ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="text-center" name="hw_[{{ $asmnt->id + 3}}]" value="{{ $asmnt->hw3 }}" type="number"></td>
                            <td class="p-0"><input title="4th Home Work ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="text-center" name="hw_[{{ $asmnt->id + 4}}]" value="{{ $asmnt->hw4 }}" type="number"></td>
                            <td class="p-0"><input title="5th Home Work ({{ $exam->hw_denominator }})" min="0" max="{{ $exam->hw_denominator }}" class="text-center" name="hw_[{{ $asmnt->id + 5}}]" value="{{ $asmnt->hw5 }}" type="number"></td>
                        </tr>
                    </table>
                </td>
                {{-- Topic test --}}
                <td>
                    <table class="data-table">
                        <tr>
                            <td class="p-0"><input title="1st Topic Test ({{ $exam->tt_denominator }})" min="0" max="{{ $exam->tt_denominator }}" class="text-center" name="tt_[{{ $asmnt->id + 1}}]" value="{{ $asmnt->tt1 }}" type="number"></td>
                            <td class="p-0"><input title="2nd Topic Test ({{ $exam->tt_denominator }})" min="0" max="{{ $exam->tt_denominator }}" class="text-center" name="tt_[{{ $asmnt->id + 2}}]" value="{{ $asmnt->tt2 }}" type="number"></td>
                            <td class="p-0"><input title="3rd Topic Test ({{ $exam->tt_denominator }})" min="0" max="{{ $exam->tt_denominator }}" class="text-center" name="tt_[{{ $asmnt->id + 3}}]" value="{{ $asmnt->tt3 }}" type="number"></td>
                        </tr>
                    </table>
                </td>
                <td class="termed-test-data"><input readonly="readonly" title="TERMED TEST ({{ $exam->tdt_denominator }})" min="0" max="{{ $exam->tdt_denominator }}" class="text-center cursor-not-allowed" name="exm_{{ $asmnt->id }}" value="{{ $asmnt->exm }}" type="number"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Update Button --}}
    <div class="text-center mt-2">
        <button type="submit" class="btn btn-primary" id="update">Update Assessments <i class="material-symbols-rounded ml-2">send</i></button>
    </div>
    {{-- Print Button --}}
    <div class="text-center mt-2">
        <a target="_blank" href="{{ route('assessments.print_assessments', [$exam_id, $my_class_id, $subject_id, $year, $section_id ?? null]) }}" class="btn btn-danger btn-lg"><i class="material-symbols-rounded mr-2">print</i> Print Assessment Records</a>
    </div>
</form>
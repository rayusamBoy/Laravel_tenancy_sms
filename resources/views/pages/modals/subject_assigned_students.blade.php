<!-- Modal -->
@php $students = Usr::getStudentRecordByIds(unserialize($rec->students_ids)) @endphp
<div class="modal fade" id="subjects-assigned-students-{{ $rec->id }}" tabindex="-1" aria-labelledby="students-assigned-heading-{{ $rec->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold break-spaces" id="students-assigned-heading-{{ $rec->id }}">{{ $s->my_class->name }} students assigned to {{ $s->name }} subject.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body p-1">
                    <ul class="nav">
                        @foreach($students as $student)
                        <li class="nav-item p-1"><a target="_blank" href="{{ route('students.show', Qs::hash($student->id)) }}">{{ $loop->iteration }} - {{ $student->user->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

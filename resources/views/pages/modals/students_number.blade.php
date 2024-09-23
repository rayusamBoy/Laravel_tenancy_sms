<!-- Modal -->
<div class="modal fade" id="students-number" tabindex="-1" aria-labelledby="students-number-breakdown" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center" id="students-number-breakdown">Students Number Breakdown</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            @php
                $class_types = Usr::getClassTypes()
            @endphp
            <div class="modal-body">
                <div class="card-body p-1">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        @foreach ($class_types as $ct)
                        <li class="nav-item"><a href="#{{ $ct->code }}" class="{{ $loop->first ? 'nav-link active' : 'nav-link' }}" data-toggle="tab">{{ $ct->name . ' (' . $ct->code . ')' }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content overflow-auto">
                    @foreach ($class_types as $ct)
                    <div class="{{ $loop->first ? 'tab-pane fade show active' : 'tab-pane fade' }}" id="{{ $ct->code }}">
                        {!! Usr::renderTable($ct->id) !!}
                    </div> 
                    @endforeach    
                </div>
            </div>
        </div>
    </div>
</div>
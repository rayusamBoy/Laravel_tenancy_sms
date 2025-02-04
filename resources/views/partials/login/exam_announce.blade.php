<!-- Exam announce -->
@if(Usr::tenancyInitilized() && Qs::isCurrentRoute('login'))
@php $exam_announces = Qs::getValidExamAnnounces() @endphp

@if($exam_announces->count() > 0)
<div class="bg-dark exam-announce-wrapper">
    <div class="overflow-hidden mx-3" id="exam-announce">
        
        @foreach($exam_announces as $exm_ann)
        <span class="p-1 badge badge-default badge-warning text-white">{{ $loop->iteration }}</span>
        <span class="pr-2 text-white align-middle">{{ ucwords(strtolower($exm_ann->message)) }} &#183; <small>{{ $exm_ann->created_at->diffForHumans() }}</small>.</span>
        @endforeach

        &nbsp;
    </div>
</div>
@endif

@endif

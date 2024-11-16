<!-- Exam announce -->
@if(Usr::tenancyInitilized())
@php $exam_announces = Qs::getValidExamAnnounces() @endphp

@if($exam_announces->count() > 0)
<div class="bg-dark overflow-hidden text-color-custom" id="exam-announce">

    @foreach($exam_announces as $exm_ann)
    <span class="p-1 badge badge-danger text-white">{{ $loop->iteration }}</span>
    <span class="pr-2 text-lightgray">{{ ucwords(strtolower($exm_ann->message)) }} &#183; <span class="text-info">{{ $exm_ann->created_at->diffForHumans() }}</span>.</span>
    @endforeach

    &nbsp;
</div>
@endif

@endif

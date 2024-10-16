{{--Unviewed Notices--}}
@if(isset($unviewed_notices) && $unviewed_notices->isNotEmpty())
<div class="card m-0 border-0" id="unviewed">
    @foreach($unviewed_notices->groupBy('user.name') as $name => $values)
    <div class="card-header">
        <span class="float-left pr-10 status-styled">Unviewed</span><i class="text-muted float-right">By - {{ ucwords($name) }}</i>
    </div>
    <div class="card-body p-1">
        @foreach($values as $untc)
        <div id="accordion-{{ $untc->id }}">
            <div class="card mb-1 border-0">
                <div class="card-header" id="headingOne-{{ $untc->id }}">
                    <h5 class="mb-0 d-flex">
                        <span class="pr-1 text-muted iteration font-size-xs">{{ $loop->iteration }}</span>
                        <button id="{{ $untc->id }}" class="btn btn-link w-100 pl-1 p-0 border-left-1 border-left-info unviewed" data-toggle="collapse" data-target="#collapseOne-{{ $untc->id }}" aria-expanded="true" aria-controls="collapseOne">
                            <span class="float-left pr-10 title">{{ $untc->title }}</span><i class="text-muted float-right">about {{ $untc->created_at->diffForHumans() }}.</i>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne-{{ $untc->id }}" class="collapse" aria-labelledby="headingOne-{{ $untc->id }}" data-parent="#accordion-{{ $untc->id }}">
                    <div class="card-body">
                        {{ $untc->body }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    <div class="p-1">
        <span class="font-size-sm text-muted status-styled">Showing {{ Qs::getPaginationFromToTotalAsString($unviewed_notices) }} unviewed notices</span>
        <span class="float-right">{{ $unviewed_notices->links() }}</span>
    </div>
</div>
@endif

{{--Viewed Notices--}}
@if(isset($viewed_notices) && $viewed_notices->isNotEmpty())
<div class="card m-0 border-0" id="viewed">
    @foreach($viewed_notices->groupBy('user.name') as $name => $values)
    <div class="card-header">
        <span class="float-left pr-10 status-styled">Viewed</span><i class="text-muted float-right">By - {{ ucwords($name) }}</i>
    </div>
    <div class="card-body p-1">
        @foreach($values as $untc)
        <div id="accordion-{{ $untc->id }}">
            <div class="card mb-1 border-0">
                <div class="card-header" id="headingOne-{{ $untc->id }}">
                    <h5 class="mb-0 d-flex">
                        <button id="{{ $untc->id }}" class="btn btn-link w-100 pl-1 p-0 border-left-1 border-left-info" data-toggle="collapse" data-target="#collapseOne-{{ $untc->id }}" aria-expanded="true" aria-controls="collapseOne">
                            <span class="float-left pr-10 break-all title break-spaces text-left">{{ $untc->title }}</span><i class="text-muted float-right">about {{ $untc->created_at->diffForHumans() }}.</i>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne-{{ $untc->id }}" class="collapse" aria-labelledby="headingOne-{{ $untc->id }}" data-parent="#accordion-{{ $untc->id }}">
                    <div class="card-body">
                        {{ $untc->body }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    <div class="p-1">
        <span class="font-size-sm text-muted status-styled">Showing {{ Qs::getPaginationFromToTotalAsString($viewed_notices) }} viewed notices</span>
        <span class="float-right">{{ $viewed_notices->links() }}</span>
    </div>
</div>
@endif
<!-- Modal -->
@foreach($notices as $ntc)
@if($ntc->viewers_ids)
@php $notice_users = Usr::getUsersByIds(json_decode($ntc->viewers_ids), true) @endphp
<div class="modal fade" id="notice-viewers-{{ $ntc->id }}" tabindex="-1" aria-labelledby="notice-viewers-heading-{{ $ntc->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center" id="notice-viewers-heading-{{ $ntc->id }}">Notice Viewers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body p-1">
                    <ul class="nav nav-tabs nav-tabs-highlight border-0">
                        @foreach(json_decode($ntc->viewers_ids) as $vid)
                        <li class="nav-item mr-2 mb-2"><a target="_blank" href="{{ route('users.show', Qs::hash($vid)) }}">{{ $loop->iteration }} - {{ $notice_users->where('id', $vid)->first()->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

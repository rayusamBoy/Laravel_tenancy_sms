<!-- Modal -->
<div class="modal fade" id="ticket-owner-modal-{{ $owner->id }}" tabindex="-1" aria-labelledby="tenant-user-info-heading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-center" id="tenant-user-info-heading">Tenant User Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td class="font-weight-bold">Name</td>
                        <td>{{ $owner->name }} ({{ str_replace("_", " ", $owner->user_type) }})</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Email</td>
                        <td><a href="mailto: {{ $owner->email }}">{{ $owner->email }}</a></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Phone</td>
                        <td><a href="tel: {{ $owner->phone ?? $owner->phone2 }}">{{ $owner->phone ?? $owner->phone2 }}</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

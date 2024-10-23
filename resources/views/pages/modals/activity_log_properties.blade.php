<!-- Modal -->
@php
$attributes = $chk->properties['attributes'] ?? [];
$old = $chk->properties['old'] ?? [];
$keys = array_keys($attributes)
@endphp
<div class="modal fade" id="activity-log-properties-{{ $chk->id }}" tabindex="-1" aria-labelled="Activity Log Properties" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td>Key</td>
                        <td>Attribute</td>
                        <td>Old</td>
                    </tr>
                    @foreach ($keys as $key)
                    <tr>
                        <td>{{ $key }}</td>
                        <td class="break-all break-spaces">{{ $attributes[$key] ?? '' }}</td>
                        <td class="break-all break-spaces">{{ $old[$key] ?? '' }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

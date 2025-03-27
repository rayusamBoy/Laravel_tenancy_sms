<!-- Modal -->
<div class="modal fade" id="confirm-message-delete" tabindex="-1" aria-label="confirm message delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-absolute bottom-0">
            <div class="modal-header display-none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">Close</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <form method="post" id="delete-user-message" action="" class="ajax-update">@csrf @method('delete')
                                <button type="submit" data-text="Deleting..." class="btn btn-sm p-0 pr-1 pl-1 m-auto btn-danger w-100 text-left p-2">Delete this message.<span class="material-symbols-rounded float-right">delete</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

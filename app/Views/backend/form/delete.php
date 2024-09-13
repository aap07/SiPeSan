<div class="modal-body">
    <input type="hidden" class="csrf_del" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="text" hidden id="id" name="id">
    <h3 class="lead text-center"><b id="dt-delete"></b></h3>
    <h6 id="dt-text" class="text-muted text-center"></h6>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="delete" type="button" class="btn btn-outline-danger">Delete</button>
</div>
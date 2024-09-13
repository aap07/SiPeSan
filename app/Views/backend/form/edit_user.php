<form id="fm-edt-user" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_edt_user" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <input type="text" hidden id="id" name="id">
                <label for="level" class="col-sm-4 col-form-label">Level</label>
                <div class="col-sm-8">
                    <select name="level" id="level" class="custom-select">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
            <label for="level" class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check-user" value="1" name="check-user">
                        <label for="check-user" class="custom-control-label">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-edt-user" class="btn btn-outline-success">Simpan Perubahan</button>
    </div>
</form>
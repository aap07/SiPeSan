<form id="fm-change-pass" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_change_pass" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body" id="form-change-pass">
            <div class="form-group row">
                <label for="current_password" class="col-sm-4 col-form-label">Current Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="new_password1" class="col-sm-4 col-form-label">New Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="new_password1" name="new_password1">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="new_password2" class="col-sm-4 col-form-label">Repeat Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="new_password2" name="new_password2">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="save-password" class="btn btn-outline-success">Simpan Password</button>
    </div>
</form>
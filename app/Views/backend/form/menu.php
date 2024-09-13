<form id="fm-menu" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_menu" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="menu" class="col-sm-4 col-form-label">Menu</label>
                <div class="col-sm-8">
                    <input type="text" hidden id="id" name="id">
                    <input type="text" class="form-control" id="menu" name="menu" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="fungsi" class="col-sm-4 col-form-label">Fungsi</label>
                <div class="col-sm-8">
                    <select name="fungsi" id="fungsi" class="custom-select">
                        <option value="0">Select Fungsi</option>
                        <option value="1">Backend</option>
                        <option value="2">Frontend</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="url" class="col-sm-4 col-form-label">URL</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="url" name="url" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
            <label for="level" class="col-sm-4 col-form-label">Submenu</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check-submenu" value="1" name="check-submenu">
                        <label for="check-submenu" class="custom-control-label">Yes</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check-menuaktif" value="1" name="check-menuaktif">
                        <label for="check-menuaktif" class="custom-control-label">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-menu" class="btn btn-outline-success">Simpan Menu</button>
    </div>
</form>
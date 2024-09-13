<form id="fm-submenu" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_submenu" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">Submenu Title</label>
                <div class="col-sm-8">
                    <input type="text" hidden id="id" name="id">
                    <input type="text" class="form-control" id="title" name="title" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="menu" class="col-sm-4 col-form-label">Menu</label>
                <div class="col-sm-8">
                    <select name="menu" id="menu" class="custom-select">
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
                <label for="icon" class="col-sm-4 col-form-label">Icon</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="icon" name="icon" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check-submenuaktif" value="1" name="check-submenuaktif">
                        <label for="check-submenuaktif" class="custom-control-label">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-submenu" class="btn btn-outline-success">Simpan Submenu</button>
    </div>
</form>
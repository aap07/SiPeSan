<form id="fm-tmbh-user" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_tmbh_user" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" name="name" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="username" name="username" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="password" name="password" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" id="email" name="email" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="tlp" class="col-sm-4 col-form-label">Tlp</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="tlp" name="tlp" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-sm-4 col-form-label">Level</label>
                <div class="col-sm-8">
                    <select name="level" id="level" class="custom-select">
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-tmbh-user" class="btn btn-outline-success">Tambah User</button>
    </div>
</form>
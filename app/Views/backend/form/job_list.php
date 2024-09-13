<form id="fm-posisi" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_posisi" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="posisi" class="col-sm-4 col-form-label">Posisi</label>
                <div class="col-sm-8">
                    <input type="text" hidden id="id" name="id">
                    <input type="text" class="form-control" id="posisi" name="posisi" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Minimum</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="min" name="min" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Maximum</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="max" name="max" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check-jobList" value="1" name="check-jobList">
                        <label for="check-jobList" class="custom-control-label">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-posisi" class="btn btn-outline-success">Simpan Posisi</button>
    </div>
</form>
<form id="fm-kriteria" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_kriteria" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="kriteria" class="col-sm-4 col-form-label">Nama Kriteria</label>
                <div class="col-sm-8">
                    <input type="text" hidden id="id" name="id">
                    <input type="text" class="form-control" id="kriteria" name="kriteria" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check-kriteriaaktif" value="1" name="check-kriteriaaktif">
                        <label for="check-kriteriaaktif" class="custom-control-label">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-kriteria" class="btn btn-outline-success">Simpan Kriteria</button>
    </div>
</form>
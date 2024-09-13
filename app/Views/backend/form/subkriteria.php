<form id="fm-subkriteria" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_subkriteria" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="level" class="col-sm-4 col-form-label">Tipe</label>
                <div class="col-sm-4">
                    <div class="custom-control custom-radio">
                        <input type="text" hidden id="id" name="id">
                        <input class="custom-control-input tipe" type="radio" id="teksRadio" value="teks" name="option" data-id="teks">
                        <label for="teksRadio" class="custom-control-label">Teks</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input tipe" type="radio" id="nilaiRadio" value="nilai" name="option" data-id="nilai">
                        <label for="nilaiRadio" class="custom-control-label">Nilai</label>
                    </div>
                </div>
            </div>
            <div id="div_teks" class="form-group row opsi" style="display: none;">
                <label for="teks_subkriteria" class="col-sm-4 col-form-label">Teks</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="teks_subkriteria" name="teks_subkriteria" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div id="div_nilai" class="opsi" style="display: none;">
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Minimum</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="min_subkriteria" name="min_subkriteria" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Maximum</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="max_subkriteria" name="max_subkriteria" autocomplete="off">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-subkriteria" class="btn btn-outline-success">Simpan Subkriteria</button>
    </div>
</form>
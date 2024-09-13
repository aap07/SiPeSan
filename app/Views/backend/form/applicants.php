<form id="fm-tmbh-applicants" class="form-horizontal" method="post">
    <input type="hidden" class="csrf_tmbh_applicants" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" hidden id="id" name="id">
                    <input type="text" class="form-control" id="name" name="name" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="pendidikan" class="col-sm-4 col-form-label">Pendidikan</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="pendidikan" name="pendidikan" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="jurusan" class="col-sm-4 col-form-label">Jurusan</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="jurusan" name="jurusan" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="nilai" class="col-sm-4 col-form-label">Nilai Ijazah</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="nilai" name="nilai" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="posisi" class="col-sm-4 col-form-label">Posisi Terakhir</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="posisi" name="posisi" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
            <label for="pengalaman" class="col-sm-4 col-form-label">Pengalaman</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="pengalaman" name="pengalaman" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="btn-tmbh-applicants" class="btn btn-outline-success">Simpan</button>
    </div>
</form>
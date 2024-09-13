<form id="fm-edt-prof" class="form-horizontal" method="post" enctype="multipart/form-data">
    <input type="hidden" class="csrf_edt_prof" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="modal-body">
        <div class="card-body" id="form-edit-profile">
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input type="hidden" id="pic_lama" name="pic_lama">
                    <input type="text" class="form-control" id="username" name="username" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" name="name" autocomplete="off">
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
                <label for="tlp" class="col-sm-4 col-form-label">Telepon</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="tlp" name="tlp" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <img id="img-profile" class="img-thumbnail img-preview">
                </div>
                <div class="col-sm-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="img_photo" name="img_photo">
                        <div class="invalid-feedback"></div>
                        <label class="custom-file-label" id="nm-img" for="img_photo"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
        <button type="submit" id="save-profile" class="btn btn-outline-success">Simpan Data</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
		bsCustomFileInput.init();
	});
</script>
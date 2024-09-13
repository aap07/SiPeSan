<div class="col-12">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Daftar Access</h3>
        </div>
        <div class="card-body box-profile">
            <!-- <input type="text" hidden id="id" name="id"> -->
            <input type="hidden" class="csrf_tbl_access" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <table id="tbl-access" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Menu</th>
                        <th>Access</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
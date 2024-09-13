<?= $this->extend('backend/template/template'); ?>
<?= $this->section('content'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $sub_title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><?= $title; ?></li>
                        <li class="breadcrumb-item"><?= $sub_title; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <!-- <div class="col-md-8"> -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Daftar Kriteria</h3>
                    </div>
                    <div class="card-body box-profile">
                        <button id="tmbh-kriteria" type="button" class="btn btn-outline-primary btn-sm mb-2"><i class=" fas fa-plus fa-sm mr-2"></i>Tambah Kriteria</button>
                        <a href="<?= base_url("recruitment/ratio"); ?>" class="btn btn-outline-success btn-sm mb-2"><i class=" fas fa-percentage fa-sm mr-2"></i>Ratio Kriteria</a>
                        <input type="hidden" class="csrf_tbl_kriteria" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                        <table id="tbl-kriteria" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kriteria</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </section>
</div>

<div class="view-modal"></div>

<?= $this->endSection('content'); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/js/backend/recruitment/kriteria_management.js'); ?>"></script>
<?= $this->endSection('scripts'); ?>
<?= $this->extend('backend/template/template'); ?>
<?= $this->section('content'); ?>

<?php

$idKriteria = $slug->id_kriteria;

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $sub_title2; ?> <?= $slug->nm_kriteria?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><?= $title; ?></li>
                        <li class="breadcrumb-item"><?= $sub_title; ?></li>
                        <li class="breadcrumb-item"><?= $sub_title2; ?></li>
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
                    <h3 class="card-title font-weight-bold">Daftar Subkriteria <?= $slug->nm_kriteria?></h3>
                </div>
                <div class="card-body box-profile">
                    <button id="tmbh-subkriteria" type="button" class="btn btn-outline-primary btn-sm mb-2"><i class=" fas fa-plus fa-sm mr-2"></i>Tambah Subkriteria</button>
                    <a href="<?= base_url("recruitment/criteria"); ?>" class="btn btn-outline-info btn-sm mb-2"><i class=" fas fa-arrow-alt-circle-left fa-sm mr-2"></i>Kembali ke Kriteria</a>
                    <a href="<?= base_url("recruitment/ratioSub/$slug->slug"); ?>" class="btn btn-outline-success btn-sm mb-2"><i class=" fas fa-percentage fa-sm mr-2"></i>Ratio Subkriteria</a>
                    <input type="hidden" class="id_kriteria" value="<?= $slug->id_kriteria; ?>" />
                    <input type="hidden" class="csrf_tbl_subkriteria" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <table id="tbl-subkriteria" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Subkriteria</th>
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
<script>
    const kriteriaId = <?= $idKriteria; ?>;
</script>
<script src="<?= base_url('assets/js/backend/recruitment/subkriteria_management.js'); ?>"></script>
<?= $this->endSection('scripts'); ?>
<?= $this->extend('backend/template/template'); ?>
<?= $this->section('content'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $sub_title2; ?> <?= $slug->nm_applicants?></h1>
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Evaluation <?= $slug->nm_applicants?></h3>
                </div>
                <div class="card-body box-profile">
                    <a href="<?= base_url("recruitment/jobapplicants"); ?>" class="btn btn-outline-info btn-sm mb-2"><i class=" fas fa-arrow-alt-circle-left fa-sm mr-2"></i>Kembali ke Kriteria</a>
                    <div class="form-group row">
                        <label for="kriteria" class="col-md-2 col-form-label">Kriteria</label>
                        <div class="col-md-10">
                            <form id="fm-penilaian-applicants" class="form-horizontal" method="post">
                                <input type="hidden" class="csrf_nilai_applicants" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Kriteria</th>
                                        <th>Nilai</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(!empty($kriteria))
                                        {
                                            foreach($kriteria as $rk)
                                            {
                                                $kriteriaId=$rk->id_kriteria;
                                                $newname = $slug->id_applicants."[".$kriteriaId."]";
                                                echo '<tr>';
                                                echo '<td>'.$rk->nm_kriteria.'</td>';
                                                echo '<td>';
                                                $dSub = ambil_subkriteria($kriteriaId);
                                                if(!empty($dSub))
                                                {						
                                                    echo '<select name="'.$newname.'"  class="custom-select">';
                                                        echo '<option>Select '.$rk->nm_kriteria.'</option>';
                                                        foreach($dSub as $rSub)
                                                        {
                                                            $o=$rSub->nm_subkriteria;
                                                            echo '<option value="'.$rSub->id_subkriteria.'">'.$o.'</option>';
                                                        }
                                                    echo '</select>';
                                                }
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="submit" id="btn-nil-applicants" class="btn btn-outline-success">Simpan Penilaian</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="view-modal"></div>

<?= $this->endSection('content'); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/js/backend/recruitment/eval_applicants_management.js'); ?>"></script>
<?= $this->endSection('scripts'); ?>
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
            <input type="hidden" class="csrf_tbl_result" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
            <table id="tbl-results" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <?php
                            foreach($arr as $kriteria)
                            {
                        ?>
                            <th><?=$kriteria->nm_kriteria;?></th>
                        <?php
                            }
                        ?>
                        <th>Total</th>
                        <th>Posisi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <?php
                        foreach($arr2 as $applicant)
                        {
                    ?>
                        <tr>
                            <td><?= $applicant->nm_applicants; ?></td>
                            <?php
                                $total=0;
                                if(!empty($arr))
                                {
                                    foreach($arr as $kriteria)
                                    {
                                        $applicantsId = $applicant->id_applicants;
                                        $kriteriaId = $kriteria->id_kriteria;
                                        $kritHasil = getKriteriaHasil($kriteriaId);
                                        $kritHasilId = $kritHasil->id_kriteria_hasil;
                                        $subkritHasil = getSubkritHasilId($applicantsId,$kriteriaId);
                                        if($subkritHasil != null){
                                            $subkritHasilId = $subkritHasil->id_subkriteria_hasil;
                                            $prioKrit = nilPrioKrit($kritHasilId) ;
                                            $prioSubkrit = nilPrioSubkrit($subkritHasilId);
                                            $priorit = $prioKrit->prioritas * $prioSubkrit->prioritas;
                                            echo '<td>'.number_format((float)$priorit, 3).'</td>';
                                        }else{
                                            echo '<td>0</td>';
                                        }
                                    }
                                }
                            ?>
                            <td><?=number_format($applicant->tot_nilai,3);?></td>
                            <td>
                            <?php 
                                foreach ($posisi as $job) {
                                    if($job->nil_min == $job->nil_max){
                                        if ($applicant->tot_nilai >= $job->nil_max) {
                                            echo $job->nm_posisi;
                                            break;
                                        }
                                    }else{
                                        if ($applicant->tot_nilai >= $job->nil_min && $applicant->tot_nilai < $job->nil_max) {
                                            echo $job->nm_posisi;
                                            break;
                                        }
                                    }
                                }
                            ?>
                            </td>
                        </tr>
                    <?php
                        }
                    ?> -->
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="view-modal"></div>

<?= $this->endSection('content'); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/js/backend/recruitment/results.js'); ?>"></script>
<?= $this->endSection('scripts'); ?>
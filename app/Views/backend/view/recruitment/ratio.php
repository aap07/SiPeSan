<?= $this->extend('backend/template/template'); ?>
<?= $this->section('content'); ?>

<?php

$jumlah=count($arr);
$irdata=array(
    1=>0.00,
    2=>0.00,
    3=>0.58,
    4=>0.90,
    5=>1.12,
    6=>1.24,
    7=>1.32,
    8=>1.41,
    9=>1.45,
    10=>1.49,
    11=>1.51,
    12=>1.48,
    13=>1.56,
    14=>1.57,
    15=>1.59,
);
$ir=0.00;
foreach($irdata as $irk=>$irv)
{
    if($irk==$jumlah)
    {
        $ir=$irv;
    }
}

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $sub_title2; ?></h1>
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
            <form id="fm-nilai" class="form-horizontal" method="post">
                <input type="hidden" class="csrf_ratio_kriteria" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <a href="<?= base_url("recruitment/criteria"); ?>" class="btn btn-outline-info btn-sm mb-2"><i class=" fas fa-arrow-alt-circle-left fa-sm mr-2"></i>Kembali ke Kriteria</a>
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Matriks Perbandingan Berpasangan</h3>
                    </div>
                    <div class="card-body box-profile">
                        <!-- <button id="tmbh-menu" type="button" class="btn btn-outline-primary btn-sm mb-2"><i class=" fas fa-plus fa-sm mr-2"></i>Tambah Menu</button> -->
                        <input type="hidden" name="crvalue" id="crvalue"/>
                        <table id="tbl-ratio" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <?php
                                    foreach($arr as $k=>$v)
                                    {
                                        ?>
                                    <th><?=$v;?></th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $noUtama=0;
                                    // $jumlah=count($arr);
                                    foreach($arr as $k2=>$v2)
                                    {		
                                        $noUtama+=1;				
                                        echo '<tr>';
                                        echo '<td>'.$v2.'</td>';
                                        $noSub=0;				
                                        $xxx='';				
                                        for($i=1;$i<=$jumlah;$i++)
                                        {
                                            $keys = array_keys($arr);
                                            $xxx=$keys[($i-1)];
                                            $newname=$k2."[".$xxx."]";
                                            $noSub+=1;
                                            if($noSub==$noUtama)
                                            {
                                                echo '<td><input type="number" id="k'.$noUtama.'b'.$noSub.'" class="form-control kolom'.$noSub.'" value="1" readonly="" title="kolom'.$noSub.'"/></td>';
                                            }else{
                                                
                                                if($noUtama > $noSub)
                                                {
                                                    echo '<td><input type="text" id="k'.$noUtama.'b'.$noSub.'" class="form-control kolom'.$noSub.'" value="0" readonly="" title="kolom'.$noSub.'"/></td>';
                                                }else{
                                                    $nilai=ambil_nilai_kriteria($k2,$xxx);
                                                    echo '<td><input name="'.$newname.'" type="text" id="k'.$noUtama.'b'.$noSub.'" value="'.$nilai.'" data-target="k'.$noSub.'b'.$noUtama.'" data-kolom="'.$noSub.'" class="form-control kolom'.$noSub.' inputnumber" title="kolom'.$noSub.'"/></td>';
                                                }				
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                    ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Jumlah</td>
                                    <?php
                                    for($h=1;$h<=$jumlah;$h++)
                                    {
                                        ?>
                                    <td><input type="text" id="total<?=$h;?>" class="form-control" value="0" title="total<?=$h;?>"  readonly=""/></td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Nilai Prioritas Kriteria</h3>
                    </div>
                    <div class="card-body box-profile">
                        <table id="tbl-ratio" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <?php
                                    foreach($arr as $k=>$v)
                                    {
                                    ?>
                                    <th><?=$v;?></th>
                                    <?php
                                    }
                                    ?>
                                    <th>Jumlah</th>
                                    <th>Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $noUtama2=0;	
                                    foreach($arr as $k2=>$v2)
                                    {
                                        $noUtama2+=1;
                                        echo '<tr>';
                                        echo '<td>'.$v2.'</td>';
                                        $noSub2=0;
                                        for($i=1;$i<=$jumlah;$i++)
                                        {
                                            $noSub2+=1;
                                            echo '<td><input type="text" id="mn-k'.$noUtama2.'b'.$noSub2.'" class="form-control" value="0" readonly=""/></td>';
                                        }
                                        echo '<td><input type="text" class="form-control" id="jml-b'.$noUtama2.'" value="0" readonly=""/></td>';
                                        echo '<td><input type="text" name="prioritas['.$k2.']" class="form-control" id="pri-b'.$noUtama2.'" value="0" readonly=""/></td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                        <button type="submit" name="submit" class="btn btn-outline-primary btn-sm mt-4"><i class=" fas fa-save fa-sm mr-2"></i>Simpan Bobot Kriteria</button>	
                    </div>
                </div>
            </form>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Matriks Penjumlahan Tiap Baris</h3>
                </div>
                <div class="card-body box-profile">
                    <table id="tbl-ratio" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <?php
                                foreach($arr as $k=>$v)
                                {
                                ?>
                                <th><?=$v;?></th>
                                <?php
                                }
                                ?>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $noUtama3=0;	
                                foreach($arr as $k3=>$v3)
                                {
                                    $noUtama3+=1;
                                    echo '<tr>';
                                    echo '<td>'.$v3.'</td>';
                                    $noSub3=0;
                                    for($i=1;$i<=$jumlah;$i++)
                                    {
                                        $noSub3+=1;
                                        echo '<td><input type="text" id="mptb-k'.$noUtama3.'b'.$noSub3.'" class="form-control" value="0" readonly=""/></td>';
                                    }
                                    echo '<td><input type="text" class="form-control" id="jmlmptb-b'.$noUtama3.'" value="0" readonly=""/></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Ratio Konsistensi Kriteria</h3>
                </div>
                <div class="card-body box-profile">
                    <table id="tbl-ratio" class="table table-bordered table-striped">
                        <thead>
                            <th>Kriteria</th>
                            <th>Jumlah Per Baris</th>
                            <th>Prioritas</th>
                            <th>Hasil</th>
                        </thead>
                        <tbody>
                            <?php
                                $noUtama4=0;	
                                foreach($arr as $k4=>$v4)
                                {
                                    $noUtama4+=1;
                                    echo '<tr>';
                                    echo '<td>'.$v4.'</td>';		
                                    echo '<td><input type="text" class="form-control" id="jmlrk-b'.$noUtama4.'" value="0" readonly=""/></td>';
                                    echo '<td><input type="text" class="form-control" id="priork-b'.$noUtama4.'" value="0" readonly=""/></td>';
                                    echo '<td><input type="text" class="form-control" id="hasilrk-b'.$noUtama4.'" value="0" readonly=""/></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" align="center"><b>TOTAL</b></td>
                                <td>
                                    <input type="text" class="form-control" id="totalrk" value="0" readonly=""/>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Hasil Perhitungan</h3>
                </div>
                <div class="card-body box-profile">
                    <table id="tbl-ratio" class="table table-bordered table-striped">
                        <thead>
                            <th>Keterangan</th>
                            <th>Nilai</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jumlah</td>
                                <td>
                                    <input type="text" class="form-control" id="sumrk" value="0" readonly=""/>
                                </td>
                            </tr>
                            <tr>
                                <td>n(Jumlah Kriteria)</td>
                                <td>
                                    <input type="text" class="form-control" id="sumkriteria" value="<?=$jumlah;?>"  readonly=""/>
                                </td>
                            </tr>
                            <tr>
                                <td>Maks(Jumlah/n)</td>
                                <td>
                                    <input type="text" class="form-control" id="summaks" value="0"  readonly=""/>
                                </td>
                            </tr>
                            <tr>
                                <td>CI((Maks-n)/n-1)</td>
                                <td>
                                    <input type="text" class="form-control" id="sumci" value="0"  readonly=""/>
                                </td>
                            </tr>
                            <tr>
                                <td>CR(CI/IR)</td>
                                <td>
                                    <input type="text" class="form-control" id="sumcr" value="0" readonly=""/>
                                </td>
                            </tr>
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
    var jumlahData = <?= $jumlah; ?>;
    var ir = <?= $ir; ?>;
</script>
<script src="<?= base_url('assets/js/backend/recruitment/ratio_management.js'); ?>"></script>
<?= $this->endSection('scripts'); ?>
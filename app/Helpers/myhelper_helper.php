<?php

use App\Models\AccessModel;
use App\Models\KriteriaNilaiModel;
use App\Models\KriteriaHasilModel;
use App\Models\SubkriteriaModel;
use App\Models\SubkriteriaNilaiModel;
use App\Models\SubkriteriaHasilModel;
use App\Models\ApplicantsModel;
use App\Models\ApplicantsNilaiModel;

function rupiah($angka)
{
    // $format = number_format($angka, 0, ",", ".");
    // $rupiah = 'Rp ' . $format . ',-';
    // return $rupiah;
    if ($angka == null) {
        return "0";
    } else {
        $jumlah_desimal = "0";
        $pemisah_desimal = ",";
        $pemisah_ribuan = ".";
        return  $rupiah = "Rp. " . number_format($angka, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan) . ",-";
    }
}

function indo_date($tgl)
{
    $d = substr($tgl, 8, 2);
    $m = substr($tgl, 5, 2);
    $y = substr($tgl, 0, 4);
    return $d . ' ' . namaBulan($m) . ' ' . $y;
}

function check_access($role_id, $menu_id)
{
    $access = new AccessModel();
    $userAccess = $access->getRoleAccess($role_id, $menu_id);
    if ($userAccess != null) {
        return "checked='checked'";
    }
}

function ambil_nilai_kriteria($dari,$tujuan)
{
    $kriteria = new KriteriaNilaiModel();
    $s=array(
        'kriteria_id_dari'=>$dari,
        'kriteria_id_tujuan'=>$tujuan,
    );
    $kn = $kriteria->getKriteriaNil($dari,$tujuan);
    if($kn != null){
        return $nilai = $kn->nilai;
    }
}

function ambil_nilai_subkriteria($dari,$tujuan)
{
    $kriteria = new SubkriteriaNilaiModel();
    $s=array(
        'subkriteria_id_dari'=>$dari,
        'subkriteria_id_tujuan'=>$tujuan,
    );
    $kn = $kriteria->getSubkriteriaNil($dari,$tujuan);
    if($kn != null){
        return $nilai = $kn->nilai;
    }
}

function ambil_subkriteria($id)
{
    $subkriteria = new SubkriteriaModel();
    $kn = $subkriteria->getSubkriteriaByKriteria($id);
    return $kn;
}

function getKriteriaHasil($id)
{
    $chasil = new KriteriaHasilModel();
    $kn = $chasil->getKritHasil($id);
    return $kn;
}

function getSubkritHasilId($idAppli, $idKrit)
{
    $subchasil = new ApplicantsNilaiModel();
    $kn = $subchasil->getSubkritHasilId($idAppli, $idKrit);
    return $kn;
}

function nilPrioKrit($id)
{
    $chasil = new KriteriaHasilModel();
    $kn = $chasil->getKriteriaHasil($id);
    return $kn;
}

function nilPrioSubkrit($id)
{
    $chasil = new SubkriteriaHasilModel();
    $kn = $chasil->getSubkriteriaHasil($id);
    return $kn;
}

function namaBulan($bulan)
{
    switch ($bulan) {
        case "01":
            return "Januari";
            break;
        case "02":
            return "Februari";
            break;
        case "03":
            return "Maret";
            break;
        case "04":
            return "April";
            break;
        case "05":
            return "Mei";
            break;
        case "06":
            return "Juni";
            break;
        case "07":
            return "Juli";
            break;
        case "08":
            return "Agustus";
            break;
        case "09":
            return "September";
            break;
        case "10":
            return "Oktober";
            break;
        case "11":
            return "November";
            break;
        case "12":
            return "Desember";
            break;
    }
}

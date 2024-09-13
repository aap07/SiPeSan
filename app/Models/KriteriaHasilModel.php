<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaHasilModel extends Model
{
    protected $table = "kriteria_hasil";
    protected $primaryKey = "id_kriteria_hasil";
    protected $returnType = "object";
    protected $allowedFields = ['id_kriteria', 'prioritas'];

    public function getKriteriaHasil($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getKritHasil($idkriteria = false){
        if ($idkriteria == false) {
            $kriteria = $this->findAll();
            return $kriteria;
        } else {
            $this->where(['kriteria_hasil.id_kriteria' => $idkriteria]);
            $kriteria = $this->get();
            return $kriteria->getRow();
        }
    }

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaNilaiModel extends Model
{
    protected $table = "kriteria_nilai";
    protected $primaryKey = "id_kriteria_nilai";
    protected $returnType = "object";
    protected $allowedFields = ['kriteria_id_dari', 'kriteria_id_tujuan', 'nilai'];

    public function getKriteriaNilai($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getKriteriaNil($dari,$tujuan)
    {
        $this->where(['kriteria_id_dari' => $dari, 'kriteria_id_tujuan' => $tujuan]);
        $list = $this->get();
        return $list->getRow();
    }

}

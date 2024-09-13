<?php

namespace App\Models;

use CodeIgniter\Model;

class SubkriteriaNilaiModel extends Model
{
    protected $table = "subkriteria_nilai";
    protected $primaryKey = "id_subkriteria_nilai";
    protected $returnType = "object";
    protected $allowedFields = ['id_kriteria', 'subkriteria_id_dari', 'subkriteria_id_tujuan', 'nilai'];

    public function getSubkriteriaNilai($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getSubkriteriaNil($dari,$tujuan)
    {
        $this->where(['subkriteria_id_dari' => $dari, 'subkriteria_id_tujuan' => $tujuan]);
        $list = $this->get();
        return $list->getRow();
    }

}

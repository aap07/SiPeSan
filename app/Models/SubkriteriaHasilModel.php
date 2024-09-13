<?php

namespace App\Models;

use CodeIgniter\Model;

class SubkriteriaHasilModel extends Model
{
    protected $table = "subkriteria_hasil";
    protected $primaryKey = "id_subkriteria_hasil";
    protected $returnType = "object";
    protected $allowedFields = ['id_kriteria', 'id_subkriteria', 'prioritas'];

    public function getSubkriteriaHasil($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

}

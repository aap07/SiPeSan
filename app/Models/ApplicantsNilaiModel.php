<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicantsNilaiModel extends Model
{
    protected $table = "applicants_nilai";
    protected $primaryKey = "id_applicants_nilai";
    protected $returnType = "object";
    protected $allowedFields = ['id_applicants', 'id_kriteria_hasil', 'id_subkriteria_hasil'];

    public function getApplicantsNilai($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getApplicantsSlug($idApplicants = false){
        if ($idApplicants == false) {
            $applicants = $this->findAll();
            return $applicants;
        } else {
            $this->where(['applicants.id_applicants' => $idApplicants]);
            $applicants = $this->get();
            return $applicants->getRow();
        }
    }

    public function getApplicants($idApplicants = false){
        if ($idApplicants == false) {
            $applicants = $this->findAll();
            return $applicants;
        } else {
            $this->where(['applicants_nilai.id_applicants' => $idApplicants]);
            $applicants = $this->get();
            return $applicants->getRow();
        }
    }
    
    public function getSubkritHasilId($idApplicants = false, $idKriteria = false){
        $array = ['id_applicants' => $idApplicants, 'id_kriteria_hasil' => $idKriteria];
        $this->where($array);
        $applicants = $this->get();
        return $applicants->getRow();
    }
}

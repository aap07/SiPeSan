<?php

namespace App\Models;

use CodeIgniter\Model;

class ResultsModel extends Model
{
    protected $table = "applicants";
    protected $where = ['deleted_at =' => null];
    //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
    //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
    protected $column_order = array('applicants.nm_applicants',NULL, NULL, NULL, NULL, NULL, 'applicants.tot_nilai', 'job_list.nm_posisi');
    protected $column_search = array('applicants.nm_applicants', 'applicants.tot_nilai', 'job_list.nm_posisi');
    protected $order = array('applicants.id_applicants' => 'asc');
    protected $db;
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }


    protected function _get_datatables_query($role)
    {
        $this->builder = $this->db->table($this->table);
        //jika ingin join formatnya adalah sebagai berikut :
        // $this->builder->join('applicants_nilai', 'applicants_nilai.id_applicants = applicants.id_applicants', 'left');
        $this->builder->join('job_list', 'job_list.id_job_list = applicants.posisi', 'left');
        // $this->builder->join('kriteria_hasil', 'kriteria_hasil.id_kriteria_hasil = applicants_nilai.id_kriteria_hasil', 'left');
        // $this->builder->join('subkriteria_hasil', 'subkriteria_hasil.id_subkriteria_hasil = applicants_nilai.id_subkriteria_hasil', 'left');
        if($role != 1){
            $this->builder->where($this->where);
        }
        //end Join
        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->builder->groupStart();
                    $this->builder->like($item, $_POST['search']['value']);
                } else {
                    $this->builder->orLike($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->builder->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $this->order = $this->order;
            $this->builder->orderBy(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables($role)
    {
        $this->_get_datatables_query($role);
        if ($_POST['length'] != -1)
            $this->builder->limit($_POST['length'], $_POST['start']);
        $query = $this->builder->get();
        return $query->getResult();
    }

    public function count_filtered($role)
    {
        $this->_get_datatables_query($role);
        return $this->builder->countAllResults();
    }

    public function count_all($role)
    {
        $this->_get_datatables_query($role);
        return $this->builder->countAllResults();
    }

    public function getApplicants($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getApplicantsSlug($slug = false){
        if ($slug == false) {
            $applicants = $this->findAll();
            return $applicants;
        } else {
            $this->where(['applicants.slug' => $slug]);
            $applicants = $this->get();
            return $applicants->getRow();
        }
    }

}

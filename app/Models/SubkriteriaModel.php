<?php

namespace App\Models;

use CodeIgniter\Model;

class SubkriteriaModel extends Model
{
    protected $table = "subkriteria";
    protected $primaryKey = "id_subkriteria";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = "create_at";
    protected $updatedField = "update_at";
    protected $deletedField = "delete_at";
    protected $allowedFields = ['id_kriteria', 'nm_subkriteria', 'tipe', 'min', 'max', 'op_min', 'op_max', 'create_at', 'update_at', 'delete_at'];
    protected $where = ['subkriteria.delete_at =' => null];
    //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
    //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
    protected $column_order = array(NULL,'kriteria.nm_kriteria','subkriteria.nm_subkriteria');
    protected $column_search = array('kriteria.nm_kriteria','subkriteria.nm_subkriteria');
    protected $order = array('subkriteria.id_subkriteria' => 'asc');
    protected $db;
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }


    protected function _get_datatables_query($role, $id_subkriteria)
    {
        $this->builder = $this->db->table($this->table);
        //jika ingin join formatnya adalah sebagai berikut :
        // $this->builder->select('menu.*, subkriteria.*');
        $this->builder->join('kriteria', 'kriteria.id_kriteria = subkriteria.id_kriteria', 'left');
        $this->builder->where(['subkriteria.id_kriteria' => $id_subkriteria]);
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

    public function get_datatables($role, $id_subkriteria)
    {
        $this->_get_datatables_query($role, $id_subkriteria);
        if ($_POST['length'] != -1)
            $this->builder->limit($_POST['length'], $_POST['start']);
        $query = $this->builder->get();
        return $query->getResult();
    }

    public function count_filtered($role, $id_subkriteria)
    {
        $this->_get_datatables_query($role, $id_subkriteria);
        return $this->builder->countAllResults();
    }

    public function count_all($role, $id_subkriteria)
    {
        $this->_get_datatables_query($role, $id_subkriteria);
        return $this->builder->countAllResults();
    }

    public function getSubkriteria($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getSubkriteriaByKriteria($id_kriteria = false)
    {
        if ($id_kriteria == false) {
            return $this->findAll();
        }
        return $this->where('id_kriteria', $id_kriteria)->findAll();
    }
}
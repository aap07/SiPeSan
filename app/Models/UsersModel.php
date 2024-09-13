<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id_user";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id_role', 'nik', 'username', 'nama', 'tlp', 'email', 'img_user', 'password', 'is_aktif', 'last_signin', 'created_at', 'updated_at', 'deleted_at'];
    protected $where = ['users.id_role !=' => 1, 'deleted_at =' => null];
    //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
    //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
    protected $column_order = array(NULL, 'users.nik', 'users.nama', 'role.nm_role', 'users.is_aktif');
    protected $column_search = array('users.nama', 'users.nik');
    protected $order = array('users.nik' => 'asc');
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
        $this->builder->join('role', 'role.id_role = users.id_role', 'left');
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

    public function cekNik()
    {
        $this->selectMax('nik');
        $query = $this->get();
        return $query->getRow();
    }

    public function hitungJumlahUser()
    {
        if ($this->where(['users.id_role' => 13])->countAllResults() > 0) {
            return $this->where(['users.id_role' => 13])->countAllResults();
        } else {
            return 0;
        }
    }

    public function getUsers($id = false)
    {
        if ($id == false) {
            $this->join('role', 'role.id_role = users.id_role', 'LEFT');
            $users = $this->findAll();
            return $users;
        } else {
            $this->join('role', 'role.id_role = users.id_role', 'LEFT')
                ->where(['users.id_user' => $id]);
            $users = $this->get();
            return $users->getRow();
        }
    }

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = "menu";
    protected $primaryKey = "id_menu";
    protected $returnType = "object";
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nm_menu', 'fungsi_menu', 'url', 'sub_menu', 'is_aktif', 'created_at', 'updated_at', 'deleted_at'];
    protected $where = ['menu.deleted_at =' => null];
    //Column Order Harus Sesuai Urutan Kolom Pada Header Tabel di bagian View
    //Awali nama kolom tabel dengan nama tabel->tanda titik->nama kolom seperti pengguna.nama
    protected $column_order = array(NULL,'menu.nm_menu','menu_fungsi_menu','menu.url','menu.is_aktif');
    protected $column_search = array('menu.nm_menu','menu.fungsi_menu','menu.url');
    protected $order = array('menu.id_menu' => 'asc');
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
        // $this->builder->join('role', 'role.id_role = users.id_role', 'left');
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

    public function getMenu($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->withDeleted()->find($id);
    }

    public function getMenuAccess($nmMenu)
    {
        $this->where(['nm_menu' => $nmMenu]);
        $list = $this->get();
        return $list->getRow();
    }

    public function getSubmenu($submenu)
    {
        $this->where(['sub_menu' => $submenu, 'is_aktif' => 1]);
        return $this->findAll();
    }

}

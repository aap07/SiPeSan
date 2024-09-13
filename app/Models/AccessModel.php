<?php

namespace App\Models;

use CodeIgniter\Model;

class AccessModel extends Model
{
    protected $table = "access";
    protected $primaryKey = "id_access";
    protected $returnType = "object";
    protected $allowedFields = ['id_role', 'id_menu'];

    public function getRoleAccess($role, $menu_id)
    {
        $this->where(['id_role' => $role, 'id_menu' => $menu_id]);
        $list = $this->get();
        return $list->getRow();
    }

    public function deleteByRole($value)
    {
        // Menghapus baris berdasarkan nilai kolom tertentu
        return $this->where('id_role', $value)->delete();
    }

}

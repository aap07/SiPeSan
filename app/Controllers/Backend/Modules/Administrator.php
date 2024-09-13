<?php

namespace App\Controllers\Backend\Modules;

use App\Controllers\BaseController;
use App\Models\AccessModel;
use App\Models\MenuModel;
use App\Models\RoleModel;
use App\Models\SubmenuModel;
use App\Models\TabelAccess;
use App\Models\UsersModel;

class Administrator extends BaseController
{
    function __construct()
    {
        $this->users = new UsersModel();
        $this->role = new RoleModel();
        $this->menu = new MenuModel();
        $this->submenu = new SubmenuModel();
        $this->tblAccess = new TabelAccess();
        $this->access = new AccessModel();
    }

    public function userManagement()
    {
        $data = array(
            'title' => 'Administrator',
            'sub_title' => 'User Management',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/administrator/user_management', $data);
    }

    public function listUser()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        $list_data = $this->users;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = $no;
            $row[] = $lists->nik;
            $row[] = $lists->nama;
            if ($lists->id_role == 1) {
                $row[] = '<p class="badge badge-info">' . $lists->nm_role . '</p>';
            } else if ($lists->id_role == 2) {
                $row[] = '<p class="badge badge-primary">' . $lists->nm_role . '</p>';
            } else if ($lists->id_role == 3) {
                $row[] = '<p class="badge badge-success">' . $lists->nm_role . '</p>';
            } else{
                $row[] = '<p class="badge badge-danger">Tidak Ada Role</p>';
            }
            $row[] = ($lists->is_aktif == 1) ? '<p class="badge badge-warning">Active</p>' : '<p class="badge badge-danger">Not Active</p>';
            
            $buttons = $lists->deleted_at == null ? 
            '<button id="detail-user" type="button" class="btn btn-outline-secondary btn-sm mr-3" data-iduser="' . $lists->id_user . '" data-toggle="tooltip" title="Detail ' . $lists->username . '" data-placement="bottom"><i class="fas fa-eye fa-sm"></i></button>
            <button id="edit-user" type="button" class="btn btn-outline-success btn-sm mr-3" data-iduser="' . $lists->id_user . '" data-toggle="tooltip" title="Edit ' . $lists->username . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>' : '';

            $buttons .= $role == 1 && $lists->deleted_at != null ? 
            '<button id="restore-user" type="button" class="btn btn-outline-info btn-sm mr-3" data-iduser="' . $lists->id_user . '" data-toggle="tooltip" title="Restore ' . $lists->username . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-user" type="button" class="btn btn-outline-danger btn-sm mr-3" data-iduser="' . $lists->id_user . '" data-toggle="tooltip" title="Remove ' . $lists->username . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : 
            '<button id="delete-user" type="button" class="btn btn-outline-danger btn-sm mr-3" data-iduser="' . $lists->id_user . '" data-toggle="tooltip" title="Delete ' . $lists->username . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function detailUser()
    {
        if ($this->request->isAJAX()) {
            $user = $this->users->getUsers($this->request->getVar('userId'));
            $output = [
                'modals' => view('backend/layout/modals'),
                'form' => view('backend/form/detail_user'),
                'user' => $user
            ];
            echo json_encode($output);
        }
    }

    public function tambahUser()
    {
        $role = $this->role->getRoleAktif();
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhUser") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/tambah_user'),
                    'role' => $role,
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtUser") {
                $user = $this->users->getUsers($this->request->getVar('userId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/edit_user'),
                    'role' => $role,
                    'user' => $user,
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveUser()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhUser") {
            $rules = [
                'username' => [
                    'rules' => 'required|trim|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Username Tidak Boleh Kosong',
                        'is_unique' => 'Username Sudah Ada'
                    ]
                ],
                'name' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Nama Tidak Boleh Kosong'
                    ]
                ],
                'email' => [
                    'rules' => 'required|trim|valid_email',
                    'errors' => [
                        'required' => 'Alamat Email Tidak Boleh Kosong'
                    ]
                ],
                'password' => [
                    'rules' => 'required|trim|min_length[6]',
                    'errors' => [
                        'required' => 'Password Tidak Boleh Kosong',
                        'min_length' => 'Password Terlalu Pendek, Minimal 6 Karakter',
                    ]
                ],
                'tlp' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'No Telepon Tidak Boleh Kosong'
                    ]
                ],
                'level' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Level Harus Dipilih'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'username' => $this->validation->getError('username'),
                    'name' => $this->validation->getError('name'),
                    'email' => $this->validation->getError('email'),
                    'password' => $this->validation->getError('password'),
                    'tlp' => $this->validation->getError('tlp'),
                    'level' => $this->validation->getError('level'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $dariDB = $this->users->cekNik();
                $nik = substr($dariDB->nik, 5, 4);
                $nikbaru = $nik + 1;
                $newnik = "KRYWN" . sprintf("%04s", $nikbaru);
                $dataUser = [
                    'id_role' => $this->request->getVar('level'),
                    'nik' => $newnik,
                    'username' => $this->request->getVar('username'),
                    'nama' => $this->request->getVar('name'),
                    'tlp' => $this->request->getVar('tlp'),
                    'email' => $this->request->getVar('email'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
                ];
                $this->users->insert($dataUser);
                $output = [
                    'status' => true,
                    'token' => $token
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtUser") {
            $rules = [
                'level' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Level Harus Dipilih'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'level' => $this->validation->getError('level'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $userId = $this->request->getVar('id');
                $dataUser = [
                    'id_role' => $this->request->getVar('level'),
                    'is_aktif' => $this->request->getVar('check-user')
                ];
                $this->users->update($userId, $dataUser);
                $output = [
                    'status' => true,
                    'token' => $token
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteUser()
    {
        $user = $this->users->getUsers($this->request->getVar('userId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteUser") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'user' => $user,
                    'text' => "Are you sure to deleted this user??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeUser") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'user' => $user,
                    'text' => "Are you sure to deleted permanent this user??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreUser") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'user' => $user,
                    'text' => "Are you sure to restore this user??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusUser()
    {
        $userId = $this->request->getVar('userId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteUser") {
            $this->users->delete($userId);
            $output = [
                'status' => true,
                'title' => "User Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeUser") {
            $this->users->delete($userId, true);
            $output = [
                'status' => true,
                'title' => "User Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreUser") {
            $this->users->update($userId, ['deleted_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "User Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function menuManagement()
    {
        $data = array(
            'title' => 'Administrator',
            'sub_title' => 'Menu Management',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/administrator/menu_management', $data);
    }

    public function listMenu()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        $list_data = $this->menu;
        // $list_data = $this->tblMenu;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $lists->nm_menu;
            if ($lists->fungsi_menu == 1) {
                $row[] = '<p class="badge badge-info">Backend</p>';
            } else if ($lists->fungsi_menu == 2){
                $row[] = '<p class="badge badge-success">Frontend</p>';
            }else {
                $row[] = '<p class="badge badge-danger">Tidak Ada Fungsi</p>';
            }
            $row[] = $lists->url;
            $row[] = ($lists->sub_menu == 1) ? '<p class="badge badge-info">Ada Submenu</p>' : '<p class="badge badge-success">Tidak Ada Submenu</p>';
            $row[] = ($lists->is_aktif == 1) ? '<p class="badge badge-warning">Active</p>' : '<p class="badge badge-danger">Not Active</p>';

            $buttons = $lists->deleted_at == null ? 
            '<button id="edit-menu" type="button" class="btn btn-outline-success btn-sm mr-3" data-idmenu="' . $lists->id_menu . '" data-toggle="tooltip" title="Edit ' . $lists->nm_menu . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>' : '';

            $buttons .= $role == 1 && $lists->deleted_at != null ? 
            '<button id="restore-menu" type="button" class="btn btn-outline-info btn-sm mr-3" data-idmenu="' . $lists->id_menu . '" data-toggle="tooltip" title="Restore ' . $lists->nm_menu . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-menu" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idmenu="' . $lists->id_menu . '" data-toggle="tooltip" title="Remove ' . $lists->nm_menu . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : 
            '<button id="delete-menu" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idmenu="' . $lists->id_menu . '" data-toggle="tooltip" title="Delete ' . $lists->nm_menu . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function tambahMenu()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhMenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/menu'),
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtMenu") {
                $menu = $this->menu->getMenu($this->request->getVar('menuId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/menu'),
                    'menu' => $menu,
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveMenu()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhMenu") {
            $rules = [
                'menu' => [
                    'rules' => 'required|trim|is_unique[menu.nm_menu]',
                    'errors' => [
                        'required' => 'Menu Tidak Boleh Kosong',
                        'is_unique' => 'Menu Sudah Ada'
                    ]
                ],
                'fungsi' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Fungsi Harus Dipilih'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'menu' => $this->validation->getError('menu'),
                    'fungsi' => $this->validation->getError('fungsi'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('check-submenu') == null) {
                    $submenu = 0;
                } else {
                    $submenu = $this->request->getVar('check-submenu');
                }
                if ($this->request->getVar('check-menuaktif') == null) {
                    $menuaktif = 0;
                } else {
                    $menuaktif = $this->request->getVar('check-menuaktif');
                }
                $dataMenu = [
                    'nm_menu' => $this->request->getVar('menu'),
                    'fungsi_menu' => $this->request->getVar('fungsi'),
                    'url' => $this->request->getVar('url'),
                    'sub_menu' => $submenu,
                    'is_aktif' => $menuaktif,
                ];
                $this->menu->insert($dataMenu);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Menu Berhasil Di Tambah"
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtMenu") {
            $menuId = $this->request->getVar('id');
            $menuLama = $this->menu->getMenu($menuId);
            if ($menuLama->nm_menu == $this->request->getVar('menu')) {
                $rule_menu = 'required|trim';
            } else {
                $rule_menu = 'required|trim|is_unique[menu.nm_menu]';
            }
            $rules = [
                'menu' => [
                    'rules' => $rule_menu,
                    'errors' => [
                        'required' => 'Menu Tidak Boleh Kosong',
                        'is_unique' => 'Menu Sudah Ada'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'menu' => $this->validation->getError('menu'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $dataMenu = [
                    'nm_menu' => $this->request->getVar('menu'),
                    'fungsi_menu' => $this->request->getVar('fungsi'),
                    'url' => $this->request->getVar('url'),
                    'sub_menu' => $this->request->getVar('check-submenu'),
                    'is_aktif' => $this->request->getVar('check-menuaktif'),
                ];
                $this->menu->update($menuId, $dataMenu);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Menu Berhasil Di Rubah"
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteMenu()
    {
        $menu = $this->menu->getMenu($this->request->getVar('menuId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteMenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'menu' => $menu,
                    'text' => "Are you sure to deleted this menu??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeMenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'menu' => $menu,
                    'text' => "Are you sure to deleted permanent this menu??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreMenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'menu' => $menu,
                    'text' => "Are you sure to restore this menu??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusMenu()
    {
        $menuId = $this->request->getVar('menuId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteMenu") {
            $this->menu->delete($menuId);
            $output = [
                'status' => true,
                'title' => "Menu Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeMenu") {
            $this->menu->delete($menuId, true);
            $output = [
                'status' => true,
                'title' => "Menu Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreMenu") {
            $this->menu->update($menuId, ['deleted_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "Menu Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function submenuManagement()
    {
        $data = array(
            'title' => 'Administrator',
            'sub_title' => 'Submenu Management',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/administrator/submenu_management', $data);
    }

    public function listSubmenu()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        // $list_data = $this->tblSubmenu;
        $list_data = $this->submenu;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = $no;
            $row[] = $lists->nm_menu;
            $row[] = $lists->title;
            $row[] = $lists->sub_url;
            $row[] = '<i class="' . $lists->icon . '"></i>';
            $row[] = ($lists->is_aktive == 1) ? '<p class="badge badge-warning">Active</p>' : '<p class="badge badge-danger">Not Active</p>';

            $buttons = $lists->delete_at == null ? 
            '<button id="edit-submenu" type="button" class="btn btn-outline-success btn-sm mr-3" data-idsub="' . $lists->id_sub . '" data-toggle="tooltip" title="Edit ' . $lists->title . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>' :'';

            $buttons .= $role == 1 && $lists->delete_at != null ? 
            '<button id="restore-submenu" type="button" class="btn btn-outline-info btn-sm mr-3" data-idsub="' . $lists->id_sub . '" data-toggle="tooltip" title="Restore ' . $lists->title . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-submenu" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idsub="' . $lists->id_sub . '" data-toggle="tooltip" title="Remove ' . $lists->title . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : 
            '<button id="delete-submenu" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idsub="' . $lists->id_sub . '" data-toggle="tooltip" title="Delete ' . $lists->title . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function tambahSubmenu()
    {
        $menu = $this->menu->getSubmenu('1');
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhSubmenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/submenu'),
                    'menu' => $menu
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtSubmenu") {
                $submenu = $this->submenu->getSubmenu($this->request->getVar('submenuId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/submenu'),
                    'menu' => $menu,
                    'submenu' => $submenu
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveSubmenu()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhSubmenu") {
            $rules = [
                'title' => [
                    'rules' => 'required|trim|is_unique[submenu.title]',
                    'errors' => [
                        'required' => 'Sub Menu Tidak Boleh Kosong',
                        'is_unique' => 'Sub Menu Sudah Ada'
                    ]
                ],
                'menu' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Menu Harus Dipilih'
                    ]
                ],
                'url' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'URL Tidak Boleh Kosong'
                    ]
                ],
                'icon' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Icon Tidak Boleh Kosong'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'title' => $this->validation->getError('title'),
                    'menu' => $this->validation->getError('menu'),
                    'url' => $this->validation->getError('url'),
                    'icon' => $this->validation->getError('icon'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                if ($this->request->getVar('check-submenuaktif') == null) {
                    $menuaktif = 0;
                } else {
                    $menuaktif = $this->request->getVar('check-submenuaktif');
                }
                $dataSubmenu = [
                    'id_menu' => $this->request->getVar('menu'),
                    'title' => $this->request->getVar('title'),
                    'sub_url' => $this->request->getVar('url'),
                    'icon' => $this->request->getVar('icon'),
                    'is_aktive' => $menuaktif,
                ];
                $this->submenu->insert($dataSubmenu);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Submenu Berhasil Di Tambah"
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtSubmenu") {
            $submenuId = $this->request->getVar('id');
            $submenuLama = $this->submenu->getSubmenu($submenuId);
            if ($submenuLama->title == $this->request->getVar('title')) {
                $rule_submenu = 'required|trim';
            } else {
                $rule_submenu = 'required|trim|is_unique[submenu.title]';
            }
            $rules = [
                'title' => [
                    'rules' => $rule_submenu,
                    'errors' => [
                        'required' => 'Sub Menu Tidak Boleh Kosong',
                        'is_unique' => 'Sub Menu Sudah Ada'
                    ]
                ],
                'menu' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Menu Harus Dipilih'
                    ]
                ],
                'url' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'URL Tidak Boleh Kosong'
                    ]
                ],
                'icon' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Icon Tidak Boleh Kosong'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'title' => $this->validation->getError('title'),
                    'menu' => $this->validation->getError('menu'),
                    'url' => $this->validation->getError('url'),
                    'icon' => $this->validation->getError('icon'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $dataSubmenu = [
                    'id_menu' => $this->request->getVar('menu'),
                    'title' => $this->request->getVar('title'),
                    'sub_url' => $this->request->getVar('url'),
                    'icon' => $this->request->getVar('icon'),
                    'is_aktive' => $this->request->getVar('check-submenuaktif'),
                ];
                $this->submenu->update($submenuId, $dataSubmenu);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Submenu Berhasil Di Rubah"
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteSubmenu()
    {
        $submenu = $this->submenu->getSubmenu($this->request->getVar('submenuId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteSubmenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'submenu' => $submenu,
                    'text' => "Are you sure to deleted this sub-menu??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeSubmenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'submenu' => $submenu,
                    'text' => "Are you sure to deleted permanent this sub-menu??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreSubmenu") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'submenu' => $submenu,
                    'text' => "Are you sure to restore this sub-menu??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusSubmenu()
    {
        $submenuId = $this->request->getVar('submenuId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteSubmenu") {
            $this->submenu->delete($submenuId);
            $output = [
                'status' => true,
                'title' => "Sub-menu Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeSubmenu") {
            $this->submenu->delete($submenuId, true);
            $output = [
                'status' => true,
                'title' => "Sub-menu Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreSubmenu") {
            $this->submenu->update($submenuId, ['delete_at' => NULL]);
            $output = [
                'status' => true,
                'title' => "Sub-menu Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function roleMenu()
    {
        $data = array(
            'title' => 'Administrator',
            'sub_title' => 'Role Menu',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/administrator/role_menu', $data);
    }

    public function listRole()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $request = \Config\Services::request();
        $list_data = $this->role;
        $role = session()->get('role');
        $list = $list_data->get_datatables($role);
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = $no;
            $row[] = $lists->nm_role;
            $row[] = ($lists->is_aktive == 1) ? '<p class="badge badge-warning">Active</p>' : '<p class="badge badge-danger">Not Active</p>';

            $buttons = $lists->delete == null ? 
            '<button id="access-role" type="button" class="btn btn-outline-warning btn-sm mr-3" data-idrole="' . $lists->id_role . '" data-toggle="tooltip" title="Access ' . $lists->nm_role . '" data-placement="bottom"><i class="fas fa-universal-access fa-sm"></i></button>
            <button id="edit-role" type="button" class="btn btn-outline-success btn-sm mr-3" data-idrole="' . $lists->id_role . '" data-toggle="tooltip" title="Edit ' . $lists->nm_role . '" data-placement="bottom"><i class="fas fa-edit fa-sm"></i></button>' : '';

            $buttons .= $role == 1 && $lists->delete != null ? 
            '<button id="restore-role" type="button" class="btn btn-outline-info btn-sm mr-3" data-idrole="' . $lists->id_role . '" data-toggle="tooltip" title="Restore ' . $lists->nm_role . '" data-placement="bottom"><i class="fas fa-recycle fa-sm"></i></button>
            <button id="remove-role" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idrole="' . $lists->id_role . '" data-toggle="tooltip" title="Remove ' . $lists->nm_role . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>' : 
            '<button id="delete-role" type="button" class="btn btn-outline-danger btn-sm mr-3" data-idrole="' . $lists->id_role . '" data-toggle="tooltip" title="Delete ' . $lists->nm_role . '" data-placement="bottom"><i class="fas fa-trash fa-sm"></i></button>';

            $row[] = $buttons;

            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all($role),
            "recordsFiltered" => $list_data->count_filtered($role),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function tambahRole()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "tmbhRole") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/role'),
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('savemethod') == "edtRole") {
                $role = $this->role->getRole($this->request->getVar('roleId'));
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/role'),
                    'role' => $role,
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveRole()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "tmbhRole") {
            $rules = [
                'role' => [
                    'rules' => 'required|trim|is_unique[role.nm_role]',
                    'errors' => [
                        'required' => 'Role Tidak Boleh Kosong',
                        'is_unique' => 'Role Sudah Ada'
                    ]
                ],
                'check-roleaktif' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Status Harus Dipilih',
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'role' => $this->validation->getError('role'),
                    'check-roleaktif' => $this->validation->getError('check-roleaktif'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $dataRole = [
                    'nm_role' => $this->request->getVar('role'),
                    'is_aktive' => $this->request->getVar('check-roleaktif'),
                ];
                $this->role->insert($dataRole);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Role Berhasil Di Tambah"
                ];
                echo json_encode($output);
            }
        } else if ($this->request->getVar('savemethod') == "edtRole") {
            $roleId = $this->request->getVar('id');
            $roleLama = $this->role->getRole($roleId);
            if ($roleLama->nm_role == $this->request->getVar('role')) {
                $rule_role = 'required|trim';
            } else {
                $rule_role = 'required|trim|is_unique[role.nm_role]';
            }
            $rules = [
                'role' => [
                    'rules' => $rule_role,
                    'errors' => [
                        'required' => 'Role Tidak Boleh Kosong',
                        'is_unique' => 'Role Sudah Ada'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'role' => $this->validation->getError('role'),
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $dataRole = [
                    'nm_role' => $this->request->getVar('role'),
                    'is_aktive' => $this->request->getVar('check-roleaktif'),
                ];
                $this->role->update($roleId, $dataRole);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Role Berhasil Di Rubah"
                ];
                echo json_encode($output);
            }
        }
    }

    public function deleteRole()
    {
        $role = $this->role->getRole($this->request->getVar('roleId'));
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('deletemethod') == "deleteRole") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'role' => $role,
                    'text' => "Are you sure to deleted this role??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "removeRole") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'role' => $role,
                    'text' => "Are you sure to deleted permanent this role??",
                ];
                echo json_encode($output);
            } else if ($this->request->getVar('deletemethod') == "restoreRole") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/delete'),
                    'role' => $role,
                    'text' => "Are you sure to restore this role??",
                ];
                echo json_encode($output);
            }
        }
    }

    public function hapusRole()
    {
        $roleId = $this->request->getVar('roleId');
        $token = csrf_hash();
        if ($this->request->getVar('deletemethod') == "deleteRole") {
            $this->role->delete($roleId);
            $output = [
                'status' => true,
                'title' => "Role Berhasil Dihapus",
                'token' => $token,
            ];
        } else if ($this->request->getVar('deletemethod') == "removeRole") {
            $this->access->deleteByRole($roleId);
            $this->role->delete($roleId, true);
            $output = [
                'status' => true,
                'title' => "Role Berhasil Dihapus Permanent",
                'token' => $token
            ];
        } else if ($this->request->getVar('deletemethod') == "restoreRole") {
            $this->role->update($roleId, ['delete' => NULL]);
            $output = [
                'status' => true,
                'title' => "Role Berhasil Di Restore",
                'token' => $token
            ];
        }
        echo json_encode($output);
    }

    public function roleAccess()
    {
        if ($this->request->isAJAX()) {
            $role = $this->role->getRole($this->request->getVar('roleId'));
            $output = [
                'modals' => view('backend/layout/modals'),
                'form' => view('backend/form/role_access'),
                'role' => $role
            ];
            echo json_encode($output);
        }
    }

    public function listAccess()
    {
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();
        $role = $this->request->getVar('roleId');
        $request = \Config\Services::request();
        $list_data = $this->tblAccess;
        $list = $list_data->get_datatables();
        $data = array();
        $no = $request->getPost("start");
        foreach ($list as $lists) {
            $no++;
            $row    = array();
            $row[] = $no;
            $row[] = $lists->nm_menu;
            $row[] = '<input type="hidden" class="csrf_access" name="' . $csrfName . '" value="' . $csrfHash . '" />
                    <input class="form-check-input" type="checkbox"' . check_access($role, $lists->id_menu) . 'data-role="' . $role . '" data-menu="' . $lists->id_menu . '">';
            $data[] = $row;
        }
        $output = array(
            "draw" => $request->getPost("draw"),
            "recordsTotal" => $list_data->count_all(),
            "recordsFiltered" => $list_data->count_filtered(),
            "data" => $data,
        );
        $output[$csrfName] = $csrfHash;
        return json_encode($output);
    }

    public function changeAccess(){
        $token = csrf_hash();
        if ($this->request->isAJAX()) {
            $role_id = $this->request->getVar('roleId');
            $menu_id = $this->request->getVar('menuId');
            if(session()->get('role') == $role_id){
                $link = 1;
            }else{
                $link = 0;
            }
            $data = [
                'id_role' => $role_id,
                'id_menu' => $menu_id
            ];
            $result = $this->access->getRoleAccess($role_id, $menu_id);
            if ($result == null) {
                $this->access->insert($data);
                $output = [
                    'status' => true,
                    'title' => "Access Berhasil Di Tambahkan",
                    'token' => $token,
                    'role' => $role_id,
                    'link' => $link
                ];
            } else {
                $this->access->delete($result->id_access);
                $output = [
                    'status' => true,
                    'title' => "Access Berhasil Di hapus",
                    'token' => $token,
                    'role' => $role_id,
                    'link' => $link
                ];
            }
            echo json_encode($output);
        }
    }
}

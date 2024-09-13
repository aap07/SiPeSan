<?php

namespace App\Controllers\Backend\Modules;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Dashboard extends BaseController
{
    protected $users;

    function __construct()
    {
        $this->users = new UsersModel();
    }

    public function index()
    {
        $data = array(
            'title' => 'Dashboard',
            'sub_title' => '',
            'totaluser' => $this->users->hitungJumlahUser(),
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/dashboard/index', $data);
    }

    public function profile()
    {
        $data = array(
            'title' => 'Profile',
            'sub_title' => '',
            'user' => $this->users->getUsers(session()->get('id_user')),
        );
        return view('backend/view/dashboard/profile', $data);
    }

    public function dataUser()
    {
        if ($this->request->isAJAX()) {
            $user = $this->users->getUsers(session()->get('id_user'));
            $output = [
                'user' => $user
            ];
            echo json_encode($output);
        }
    }

    public function editProfile()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getVar('savemethod') == "dataProfile") {
                $user = $this->users->getUsers(session()->get('id_user'));
                $output = [
                    'user' => $user,
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/edit_profile'),
                ];
                echo json_encode($output);
            }else if ($this->request->getVar('savemethod') == "changePass") {
                $output = [
                    'modals' => view('backend/layout/modals'),
                    'form' => view('backend/form/change_pass'),
                ];
                echo json_encode($output);
            }
        }
    }

    public function saveProfile()
    {
        $token = csrf_hash();
        if ($this->request->getVar('savemethod') == "dataProfile") {
            $namaLama = $this->users->getUsers(session()->get('id_user'));
            if ($namaLama->username == $this->request->getVar('username')) {
                $rule_username = 'required|trim';
            } else {
                $rule_username = 'required|trim|is_unique[users.username]';
            }
            $rules = [
                'username' => [
                    'rules' => $rule_username,
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
                'tlp' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'No Telepon Tidak Boleh Kosong'
                    ]
                ],
                'img_photo' => [
                    'rules' => 'max_size[img_photo,4096]|is_image[img_photo]|mime_in[img_photo,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran Foto Terlalu Besar',
                        'is_image' => 'Yang Anda Pilih Bukan Foto',
                        'mime_in' => 'Yang Anda Pilih Bukan Foto',
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'username' => $this->validation->getError('username'),
                    'name' => $this->validation->getError('name'),
                    'email' => $this->validation->getError('email'),
                    'tlp' => $this->validation->getError('tlp'),
                    'img_photo' => $this->validation->getError('img_photo')
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $pic_user = $this->request->getFile('img_photo');
                if($pic_user->getError() == 4){
                    $pic = $this->request->getVar('pic_lama');
                }else{
                    $pic = $pic_user->getRandomName();
                    $pic_user->move('assets/img/profile',$pic);
                    $picLama = $this->users->getUsers(session()->get('id_user'));
                    if($picLama->img_user != 'default.svg'){
                        unlink('assets/img/profile/' . $this->request->getVar('pic_lama'));
                    }
                }
                $dataUser = [
                    'username' => $this->request->getVar('username'),
                    'nama' => $this->request->getVar('name'),
                    'email' => $this->request->getVar('email'),
                    'tlp' => $this->request->getVar('tlp'),
                    'img_user' => $pic
                ];
                $this->users->update(session()->get('id_user'), $dataUser);
                $output = [
                    'status' => true,
                    'token' => $token,
                    'title' => "Data Berhasil Di Rubah",
                ];
                echo json_encode($output);
            }
        } elseif ($this->request->getVar('savemethod') == "changePass") {
            $rules = [
                'current_password' => [
                    'rules' => 'required|trim',
                    'errors' => [
                        'required' => 'Current Password Tidak Boleh Kosong'
                    ]
                ],
                'new_password1' => [
                    'rules' => 'required|trim|min_length[6]',
                    'errors' => [
                        'required' => 'New Password Tidak Boleh Kosong',
                        'min_length' => 'New Password Terlalu pendek, Minimal 6 Karakter'
                    ]
                ],
                'new_password2' => [
                    'rules' => 'required|trim|matches[new_password1]',
                    'errors' => [
                        'required' => 'Confirmed Password Tidak Boleh Kosong',
                        'matches' => 'Confirmed Password Tidak sesuai'
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $error = [
                    'current_password' => $this->validation->getError('current_password'),
                    'new_password1' => $this->validation->getError('new_password1'),
                    'new_password2' => $this->validation->getError('new_password2')
                ];
                $output = [
                    'status' => false,
                    'errors' => $error,
                    'token' => $token
                ];
                echo json_encode($output);
            } else {
                $oldPass = $this->request->getVar('current_password');
                $newPass = $this->request->getVar('new_password1');
                $user = $this->users->getUsers(session()->get('id_user'));
                if (!password_verify($oldPass, $user->password)) {
                    $output = [
                        'status' => true,
                        'verified' => "1",
                        'token' => $token
                    ];
                } else {
                    if ($oldPass == $newPass) {
                        $output = [
                            'status' => true,
                            'verified' => "2",
                            'token' => $token
                        ];
                    } else {
                        $data['password'] = password_hash($newPass, PASSWORD_DEFAULT);
                        $this->users->update(session()->get('id_user'), $data);
                        $output = [
                            'status' => true,
                            'verified' => "3",
                            'token' => $token
                        ];
                    }
                }
                echo json_encode($output);
            }
        }
    }
}

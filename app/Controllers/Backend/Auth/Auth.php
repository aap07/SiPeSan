<?php

namespace App\Controllers\Backend\Auth;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\I18n\Time;

class Auth extends BaseController
{
    // protected $users;

    function __construct()
    {
        $this->users = new UsersModel();
    }

    public function index()
    {
        $data = array(
            'title' => 'Sign In',
            'sub_title' => 'SiPeSan',
            'validation' => \Config\Services::validation()
        );
        return view('backend/view/auth/index', $data);
    }
    
    public function login()
    {
        $rules = [
            'username' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => '{field} Tidak Boleh Kosong'
                ]
            ],
            'password' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => '{field} Tidak Boleh Kosong'
                ]
            ]
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }else{
            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            $dataUser = $this->users->where(['username' => $username,'is_aktif' => '1'])->first();
            if ($dataUser) {
                if (password_verify($password, $dataUser->password)) {
                    session()->set([
                        'id_user' => $dataUser->id_user,
                        'username' => $dataUser->username,
                        'role' => $dataUser->id_role,
                        'logged_in' => TRUE
                    ]);
                    $lastsignin = [
                        "last_signin" => Time::now()
                    ];
                    $this->users->update(session()->get('id_user'), $lastsignin);
                    session()->setFlashdata('message', 'Login Berhasil');
                    return redirect()->to('dashboard');
                } else {
                    session()->setFlashdata('messageerror', 'Username & Password Tidak Sesuai');
                    return redirect()->back();
                }
            } else {
                session()->setFlashdata('messageerror', 'Username & Password Tidak Sesuai');
                return redirect()->back();
            }
        }
    }

    public function logout()
    {
        session()->remove([
            'username',
            'nik',
            'role',
            'logged_in',
        ]);
        session()->setFlashdata('message', 'Anda Berhasil Logout');
        return redirect()->to('/');
    }

    public function blocked()
    {
        $data['title'] = 'Forbiden';
        $data['sub_title'] = 'Access';
        return view('backend/view/auth/blocked', $data);
    }
}
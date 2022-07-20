<?php

namespace App\Controllers;

use Myth\Auth\Models\UserModel;
use \Myth\Auth\Authorization\GroupModel;

class Admin extends BaseController
{
    protected $db, $builder, $groupBuilder;
    protected $userModel, $groupModel;

    public function __construct()
    {
        $this->db      = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->groupBuilder = $this->db->table('auth_groups');
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        // $users = new \myth\Auth\Models\UserModel();

        $this->builder->select('users.id as userid, username, email, name as groupname');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $query = $this->builder->get();


        $data = [
            'judul' => 'Halaman Admin',
            'users' => $query->getResultObject(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/index', $data);
    }

    public function detail($id = null)
    {
        $this->builder->select('users.id as userid, username, email, fullname, user_image, auth_groups.id as groupid, auth_groups.name as groupname');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id', $id);
        $user = $this->builder->get();

        $this->groupBuilder->select('id, name');
        $groups = $this->groupBuilder->get();

        $data = [
            'judul' => 'User Detail',
            'user' => $user->getRowObject(),
            'groups' => $groups->getResultObject(),
            'validation' => \Config\Services::validation(),
        ];

        if (empty($data['user'])) {
            return redirect('admin');
        }

        return view('admin/detail', $data);
    }

    public function updateUser()
    {
        // d($this->request->getPost());
        // d($this->request->getFiles());
        $this->builder->select('users.id as userid, username, email, fullname, user_image, auth_groups_users.group_id as groupid');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->where('users.id', $this->request->getVar('userid'));
        $query = $this->builder->get();
        $dataLama = $query->getRowArray();

        // dd($dataLama);

        if ($dataLama['username'] == $this->request->getVar('username')) {
            $usernameRule = 'required|alpha_numeric|min_length[3]|max_length[30]';
        } else {
            $usernameRule = 'required|alpha_numeric|min_length[3]|max_length[30]|is_unique[users.username]';
        }

        if ($dataLama['email'] == $this->request->getVar('email')) {
            $emailRule = 'required|valid_emails';
        } else {
            $emailRule = 'required|is_unique[users.email]|valid_emails';
        }

        $fields = [
            'username' => [
                'rules' => $usernameRule,
                'errors' => [
                    'required' => 'Username tidak boleh kosong',
                    'alpha_numeric' => 'Username hanya boleh mengandung huruf dan angka',
                    'min_length' => 'Username tidak boleh kurang dari 3 karakter',
                    'max_length' => 'Username tidak boleh lebih dari 30 karakter',
                    'is_unique' => 'Username tidak tersedia',
                ]
            ],
            'fullname' => [
                'rules'     => 'string',
                'errors'    => [
                    'string'  => 'Nama lengkap hanya boleh mengandung huruf'
                ]
            ],
            'email' => [
                'rules'     => $emailRule,
                'errors'    => [
                    'required'  => 'Email tidak boleh kosong',
                    'is_unique' => 'Email sudah terdaftar',
                    'valid_emails' => 'Email tidak valid'
                ]
            ],
            'file' => [
                'rules' => 'mime_in[file,image/png,image/jpg,image/jpeg]|max_size[file,2048]',
                'errors' => [
                    'mime_in' => 'Ekstensi file harus PNG/JPG/JPEG',
                    'max_size' => 'Ukuran file tidak boleh lebih dari 2 MB'
                ]
            ]
        ];

        if (!$this->validate($fields)) {
            // dd($this->validation->getErrors());

            return redirect()->to('admin/' . $this->request->getVar('userid'))->withInput();
        } else {
            $userImage = $this->request->getFile('file');

            if ($userImage->getError() == 4) {
                $fileName = $dataLama['user_image'];
            } else {
                $fileName = $userImage->getRandomName();
                $userImage->move('assets/img/', $fileName);

                if ($dataLama['user_image'] != 'default.svg') {
                    unlink('assets/img/' . $dataLama['user_image']);
                }
            }

            $this->userModel->save([
                'id' => $this->request->getVar('userid'),
                'username' => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
                'fullname' => $this->request->getVar('fullname'),
                'user_image' => $fileName
            ]);

            $this->groupModel->removeUserFromGroup($this->request->getVar('userid'), $dataLama['groupid']);
            $this->groupModel->addUserToGroup($this->request->getVar('userid'), $this->request->getVar('groupid'));

            session()->setFlashdata('pesan', 'Data user berhasil diubah');

            return redirect()->back();
        }
    }
}

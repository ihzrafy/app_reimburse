<?php
namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        return view('admin/dashboard', ['title' => 'Admin Dashboard']);
    }

    public function createUser()
    {
        $userModel = new \App\Models\UserModel();

        // Data yang diambil dari form
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash password
            'role'     => $this->request->getPost('role'), // admin atau pegawai
        ];

        $userModel->insert($data);
        return redirect()->to('/admin/users')->with('success', 'User successfully created.');
    }

    public function register()
    {
        $userModel = new \App\Models\UserModel();

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash password
            'role'     => 'pegawai', // Default role untuk user baru
        ];

        $userModel->insert($data);
        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }

    public function updatePassword($id)
    {
        $userModel = new \App\Models\UserModel();

        $newPassword = $this->request->getPost('new_password');
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $userModel->update($id, ['password' => $hashedPassword]);
        return redirect()->to('/profile')->with('success', 'Password successfully updated.');
    }



}

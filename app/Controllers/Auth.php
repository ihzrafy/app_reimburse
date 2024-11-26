<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EmployeeModel; // Tambahkan model Employee
use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        $userModel = new UserModel();
        $employeeModel = new EmployeeModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Ambil data pegawai berdasarkan employee_id
            $employee = $employeeModel->where('employee_id', $user['employee_id'])->first();

            // Set session
            session()->set([
                'user_id'       => $user['id'],
                'username'      => $user['username'],
                'role'          => $user['role'],
                'employee_id'   => $user['employee_id'], // Employee ID
                'full_name'     => $employee['full_name'] ?? 'Employee', // Nama Pegawai
                'logged_in'     => true,
            ]);

            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/pegawai/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Invalid username or password.');
    }



    public function logout()
    {
        session()->destroy(); // Menghapus semua session
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    public function register()
    {
        return view('auth/register'); // Menampilkan form register
    }

    public function processRegister()
    {
        $userModel = new UserModel();
        $employeeModel = new EmployeeModel();

        // Ambil input dari form
        $username = $this->request->getPost('username');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $fullName = $this->request->getPost('full_name');

        // Generate Employee ID (dari metode di EmployeeModel)
        $employeeId = $employeeModel->generateEmployeeId();

        // Mulai transaksi untuk memastikan konsistensi data
        $db = \Config\Database::connect();
        $db->transStart();

        // Simpan ke tabel employees terlebih dahulu
        $employeeModel->insert([
            'employee_id' => $employeeId,
            'full_name'   => $fullName,
        ]);

        // Simpan ke tabel users dengan full_name dan referensikan employee_id
        $userModel->insert([
            'username'     => $username,
            'password'     => $password,
            'role'         => 'pegawai',  // Role default untuk user baru adalah pegawai
            'employee_id'  => $employeeId, // Simpan employee_id untuk referensi
            'full_name'    => $fullName,  // Simpan nama di tabel users
        ]);

        // Selesaikan transaksi
        $db->transComplete();

        if ($db->transStatus() === false) {
            // Jika terjadi error, rollback dan tampilkan pesan error
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }




}

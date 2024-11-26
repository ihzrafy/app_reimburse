<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
            ],
            [
                'username' => 'user1',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role'     => 'pegawai',
            ],
        ];

        // Insert ke tabel users
        $this->db->table('users')->insertBatch($data);
    }
}

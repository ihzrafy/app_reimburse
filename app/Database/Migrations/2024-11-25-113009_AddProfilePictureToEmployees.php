<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfilePictureToEmployees extends Migration
{
    public function up()
    {
        $this->forge->addColumn('employees', [
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true, // Kolom dapat bernilai NULL jika belum ada foto
                'after' => 'full_name', // Letakkan setelah kolom full_name
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', 'profile_picture');
    }
}

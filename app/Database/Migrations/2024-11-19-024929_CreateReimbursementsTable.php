<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReimbursementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'limit' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // Bisa 'UNLIMITED' atau angka
            ],
            'expired_in' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // Bisa tanggal atau 'UNLIMITED'
            ],
            'effective_date' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('reimbursements');
    }

    public function down()
    {
        $this->forge->dropTable('reimbursements');
    }
}

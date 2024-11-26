<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReimbursementRequestsTable extends Migration
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
            'transaction_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'employee' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'reimbursement_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'effective_date' => [
                'type' => 'DATE',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Approved', 'Rejected'],
                'default'    => 'Pending',
            ],
            'attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->addKey('id', true); // Primary Key
        $this->forge->addKey('transaction_id', true); // Unique Key
        $this->forge->createTable('reimbursement_requests');
    }

    public function down()
    {
        $this->forge->dropTable('reimbursement_requests');
    }
}

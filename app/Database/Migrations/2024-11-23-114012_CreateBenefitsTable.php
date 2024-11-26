<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBenefitsTable extends Migration
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
            'benefit_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'request_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'paid_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'benefit_description' => [
                'type' => 'TEXT',
                'null' => true,
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

        // Primary Key
        $this->forge->addKey('id', true);

        // Foreign Key Constraint
        $this->forge->addForeignKey('transaction_id', 'reimbursement_requests', 'transaction_id', 'CASCADE', 'CASCADE');

        // Create Table
        $this->forge->createTable('benefits');
    }

    public function down()
    {
        // Drop Table
        $this->forge->dropTable('benefits');
    }
}

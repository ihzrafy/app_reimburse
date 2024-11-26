<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignmentEmployeesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'assignment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'employee_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'balance' => [
            'type'       => 'DECIMAL',
            'constraint' => '15,2',
            'default'    => 0.00,
            'after'      => 'employee_id', // Adjust the position as needed
        ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('assignment_id', 'assignments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assignment_employees');
    }

    public function down()
    {
        $this->forge->dropTable('assignment_employees', 'balance');
    }
}

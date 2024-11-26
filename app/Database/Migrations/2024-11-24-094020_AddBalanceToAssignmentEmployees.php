<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBalanceToAssignmentEmployees extends Migration
{
    public function up()
    {
        $this->forge->addColumn('assignment_employees', [
            'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
                'default' => 0.00,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('assignment_employees', 'balance');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTotalPaidToBenefits extends Migration
{
    public function up()
    {
        $this->forge->addColumn('benefits', [
            'total_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'after'      => 'paid_amount', // Menempatkan kolom setelah paid_amount
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('benefits', 'total_paid');
    }
}

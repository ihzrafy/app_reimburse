<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';
    protected $allowedFields = ['employee_id', 'full_name', 'profile_picture'];
    protected $useTimestamps = false; // Nonaktifkan timestamps

    public function generateEmployeeId()
    {
        $lastId = $this->orderBy('employee_id', 'DESC')->first();
        if ($lastId) {
            $lastNumber = (int) substr($lastId['employee_id'], -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        return 'TDI-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}

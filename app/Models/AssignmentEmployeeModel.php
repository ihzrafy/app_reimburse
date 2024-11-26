<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentEmployeeModel extends Model
{
    protected $table = 'assignment_employees';
    protected $primaryKey = 'id';
    protected $allowedFields = ['assignment_id', 'employee_id', 'balance']; // Tambahkan 'balance'
    public $timestamps = false;
}

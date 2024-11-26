<?php

namespace App\Models;

use CodeIgniter\Model;

class ReimbursementModel extends Model
{
    protected $table = 'reimbursements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'limit', 'expired_in', 'effective_date'];
    protected $useTimestamps = true;

    // Validation rules
    protected $validationRules = [
        'name'           => 'required|max_length[255]',
        'limit'          => 'required|max_length[50]',
        'expired_in'     => 'required|max_length[50]',
        'effective_date' => 'required|valid_date',
    ];
}

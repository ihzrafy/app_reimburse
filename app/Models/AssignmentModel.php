<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentModel extends Model
{
    protected $table            = 'assignments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['reimbursement_id', 'type', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
}

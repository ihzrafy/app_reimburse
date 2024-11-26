<?php

namespace App\Models;

use CodeIgniter\Model;

class BenefitModel extends Model
{
    protected $table = 'benefits';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id',
        'benefit_name',
        'request_amount',
        'paid_amount',
        'benefit_description',
    ];
    protected $useTimestamps = true;
}

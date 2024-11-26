<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'profile_picture', 'role', 'employee_id', 'full_name', 'created_at'];

    protected $useTimestamps = true;

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}

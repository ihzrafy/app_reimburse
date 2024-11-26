<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class BaseController extends \CodeIgniter\Controller
{
    protected $employeeName;

    public function __construct()
    {
        $this->getEmployeeName();
    }

    private function getEmployeeName()
    {
        // Ambil employee_id dari session
        $employeeId = session()->get('employee_id');

        if ($employeeId) {
            $employeeModel = new EmployeeModel();
            $employee = $employeeModel->where('employee_id', $employeeId)->first();

            if ($employee) {
                $this->employeeName = $employee['full_name'];
            } else {
                $this->employeeName = 'Unknown';
            }
        } else {
            $this->employeeName = 'Guest';
        }
    }
}

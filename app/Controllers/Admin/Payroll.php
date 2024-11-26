<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReimbursementRequestModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Payroll extends BaseController
{
    protected $reimbursementRequestModel;

    public function __construct()
    {
        $this->reimbursementRequestModel = new ReimbursementRequestModel();
    }

    public function index()
{
    $db = \Config\Database::connect();

    // Ambil filter dari input
    $year = $this->request->getGet('year');
    $month = $this->request->getGet('month');

    // Query untuk data karyawan
    $builder = $db->table('assignment_employees')
        ->select('employees.employee_id, employees.full_name, reimbursements.name as reimbursement_name, assignment_employees.balance')
        ->join('employees', 'employees.employee_id = assignment_employees.employee_id')
        ->join('assignments', 'assignments.id = assignment_employees.assignment_id')
        ->join('reimbursements', 'reimbursements.id = assignments.reimbursement_id');

    if ($year) {
        $builder->where('YEAR(assignments.created_at)', $year);
    }
    if ($month) {
        $builder->where('MONTH(assignments.created_at)', $month);
    }

    $employees = $builder->get()->getResultArray();

    // Query untuk klaim
    $claimBuilder = $db->table('benefits')
        ->select('reimbursement_requests.employee, MONTH(reimbursement_requests.created_at) as claim_month, SUM(benefits.paid_amount) as total_claim')
        ->join('reimbursement_requests', 'reimbursement_requests.transaction_id = benefits.transaction_id')
        ->groupBy('reimbursement_requests.employee, claim_month');

    if ($year) {
        $claimBuilder->where('YEAR(reimbursement_requests.created_at)', $year);
    }
    if ($month) {
        $claimBuilder->where('MONTH(reimbursement_requests.created_at)', $month);
    }

    $claims = $claimBuilder->get()->getResultArray();

    // Format klaim data per bulan
    $claimData = [];
    foreach ($claims as $claim) {
        $claimData[$claim['employee']][$claim['claim_month']] = $claim['total_claim'];
    }

    return view('admin/page/payroll/index', [
        'employees' => $employees,
        'claimData' => $claimData,
        'year' => $year,
        'month' => $month,
    ]);
}


    


    public function downloadExcel()
    {
        $db = \Config\Database::connect();

        // Ambil filter dari input
        $year = $this->request->getGet('year');
        $month = $this->request->getGet('month');

        // Query untuk data karyawan
        $builder = $db->table('assignment_employees')
            ->select('employees.employee_id, employees.full_name, reimbursements.name as reimbursement_name, assignment_employees.balance')
            ->join('employees', 'employees.employee_id = assignment_employees.employee_id')
            ->join('assignments', 'assignments.id = assignment_employees.assignment_id')
            ->join('reimbursements', 'reimbursements.id = assignments.reimbursement_id');

        if ($year) {
            $builder->where('YEAR(assignments.created_at)', $year);
        }
        if ($month) {
            $builder->where('MONTH(assignments.created_at)', $month);
        }

        $employees = $builder->get()->getResultArray();

        // Query untuk klaim
        $claimBuilder = $db->table('benefits')
            ->select('reimbursement_requests.employee, MONTH(reimbursement_requests.created_at) as claim_month, SUM(benefits.paid_amount) as total_claim')
            ->join('reimbursement_requests', 'reimbursement_requests.transaction_id = benefits.transaction_id')
            ->groupBy('reimbursement_requests.employee, claim_month');

        if ($year) {
            $claimBuilder->where('YEAR(reimbursement_requests.created_at)', $year);
        }
        if ($month) {
            $claimBuilder->where('MONTH(reimbursement_requests.created_at)', $month);
        }

        $claims = $claimBuilder->get()->getResultArray();

        $claimData = [];
        foreach ($claims as $claim) {
            $claimData[$claim['employee']][$claim['claim_month']] = $claim['total_claim'];
        }

        // Membuat file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'Full Name');
        $sheet->setCellValue('C1', 'Reimbursement Name');
        $sheet->setCellValue('D1', 'Balance');

        $columns = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $col = 'E';
        foreach ($columns as $column) {
            $sheet->setCellValue($col . '1', $column);
            $col++;
        }

        // Isi Data
        $row = 2;
        foreach ($employees as $employee) {
            $sheet->setCellValue('A' . $row, $employee['employee_id']);
            $sheet->setCellValue('B' . $row, $employee['full_name']);
            $sheet->setCellValue('C' . $row, $employee['reimbursement_name']);
            $sheet->setCellValue('D' . $row, $employee['balance']);

            $col = 'E';
            for ($i = 1; $i <= 12; $i++) {
                $sheet->setCellValue(
                    $col . $row,
                    isset($claimData[$employee['employee_id']][$i])
                        ? $claimData[$employee['employee_id']][$i]
                        : 0
                );
                $col++;
            }

            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"reimbursement_summary.xlsx\"");
        $writer->save('php://output');
        exit;
    }



        



}

<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReimbursementModel;
use App\Models\AssignmentEmployeeModel;
use App\Models\EmployeeModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Reimbursement extends BaseController
{
    protected $reimbursementModel; // Deklarasikan properti di sini

    public function __construct()
    {
        // Inisialisasi model
        $this->reimbursementModel = new ReimbursementModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Reimbursement List',
            'reimbursements' => $this->reimbursementModel->findAll(),
        ];

        return view('admin/page/reimbursement/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Reimbursement',
        ];
        return view('admin/page/reimbursement/create', $data);
    }

    public function store()
    {
        if ($this->validate($this->reimbursementModel->getValidationRules())) {
            $this->reimbursementModel->save($this->request->getPost());
            session()->setFlashdata('success', 'Reimbursement created successfully.');
            return redirect()->to('/admin/reimbursement');
        }

        session()->setFlashdata('error', 'Failed to create reimbursement. Please check the form.');
        return redirect()->back()->withInput();
    }

    public function edit($id)
    {
        $reimbursement = $this->reimbursementModel->find($id);

        if (!$reimbursement) {
            return redirect()->to('/admin/reimbursement')->with('error', 'Reimbursement not found.');
        }

        $data = [
            'title' => 'Edit Reimbursement',
            'reimbursement' => $reimbursement,
        ];

        return view('admin/page/reimbursement/edit_policy', $data);
    }

    public function update($id)
    {
        if ($this->validate($this->reimbursementModel->getValidationRules())) {
            $this->reimbursementModel->update($id, $this->request->getPost());
            session()->setFlashdata('success', 'Reimbursement updated successfully.');
            return redirect()->to('/admin/reimbursement');
        }

        session()->setFlashdata('error', 'Failed to update reimbursement. Please check the form.');
        return redirect()->back()->withInput();
    }

    public function delete($id)
    {
        if ($this->reimbursementModel->delete($id)) {
            session()->setFlashdata('success', 'Reimbursement deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete the reimbursement.');
        }

        return redirect()->to('/admin/reimbursement');
    }

    public function viewEmployees($reimbursementId)
    {
        $assignmentEmployeeModel = new AssignmentEmployeeModel();
        $employeeModel = new EmployeeModel();

        // Ambil data pegawai yang terhubung dengan reimbursement ID tertentu
        $employees = $assignmentEmployeeModel
            ->select('assignment_employees.balance, employees.employee_id, employees.full_name')
            ->join('employees', 'employees.employee_id = assignment_employees.employee_id')
            ->join('assignments', 'assignments.id = assignment_employees.assignment_id')
            ->where('assignments.reimbursement_id', $reimbursementId)
            ->findAll();

        // Ambil informasi tentang reimbursement
        $reimbursement = $this->reimbursementModel->find($reimbursementId);

        if (!$reimbursement) {
            return redirect()->to('/admin/reimbursement')->with('error', 'Reimbursement not found.');
        }

        // Kirim data ke view
        $data = [
            'title' => 'Employee Balances',
            'employees' => $employees,
            'reimbursement' => $reimbursement,
        ];

        return view('admin/page/reimbursement/employee_balances', $data);
    }
    public function newPolicy()
{
    $data = [
        'title' => 'New Policy',
        'policies' => [
            [
                'id' => 1,
                'name' => 'Medical Claim',
                'limit' => 1000000,
                'expired_in' => '31 December',
                'effective_date' => '2024-01-01',
            ],
            [
                'id' => 2,
                'name' => 'Transportasi',
                'limit' => 'UNLIMITED',
                'expired_in' => 'UNLIMITED',
                'effective_date' => '2024-01-01',
            ],
        ],
    ];

    return view('admin/page/reimbursement/new_policy', $data);
}
public function export()
{
    $reimbursementModel = new \App\Models\ReimbursementModel();
    $data = $reimbursementModel->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Limit');
    $sheet->setCellValue('D1', 'Expired In');
    $sheet->setCellValue('E1', 'Effective Date');

    // Set data
    $row = 2;
    foreach ($data as $reimbursement) {
        $sheet->setCellValue("A$row", $reimbursement['id']);
        $sheet->setCellValue("B$row", $reimbursement['name']);
        $sheet->setCellValue("C$row", $reimbursement['limit']);
        $sheet->setCellValue("D$row", $reimbursement['expired_in']);
        $sheet->setCellValue("E$row", $reimbursement['effective_date']);
        $row++;
    }

    // Download as Excel file
    $writer = new Xlsx($spreadsheet);
    $fileName = 'reimbursements.xlsx';

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}


public function import()
{
    $file = $this->request->getFile('file');

    if ($file->isValid() && !$file->hasMoved()) {
        $spreadsheet = IOFactory::load($file->getTempName());
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $reimbursementModel = new \App\Models\ReimbursementModel();

        // Mulai dari baris kedua (baris pertama adalah header)
        foreach ($data as $index => $row) {
            if ($index == 1) continue; // Skip header row
            $reimbursementModel->insert([
                'name'           => $row['B'],
                'limit'          => $row['C'],
                'expired_in'     => $row['D'],
                'effective_date' => $row['E'],
            ]);
        }

        session()->setFlashdata('success', 'Data imported successfully.');
    } else {
        session()->setFlashdata('error', 'Failed to import file.');
    }

    return redirect()->to('/admin/reimbursement');
}

public function details($id)
{
    $reimbursementModel = new ReimbursementModel();
    $reimbursement = $reimbursementModel->find($id);

    if (!$reimbursement) {
        return redirect()->to('/admin/reimbursement')->with('error', 'Reimbursement not found.');
    }

    // Data untuk ditampilkan pada halaman details
    $data = [
        'title' => 'Reimbursement Details',
        'reimbursement' => $reimbursement,
    ];

    return view('admin/page/reimbursement/details', $data);
}

}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AssignmentModel;
use App\Models\ReimbursementModel;

class AssignmentController extends BaseController
{
    protected $assignmentModel;
    protected $reimbursementModel;

    public function __construct()
    {
        $this->assignmentModel = new AssignmentModel();
        $this->reimbursementModel = new ReimbursementModel();
    }

    public function index()
    {
        $data = [
            'assignments' => $this->assignmentModel->select('assignments.*, reimbursements.name as reimbursement_name')
                ->join('reimbursements', 'reimbursements.id = assignments.reimbursement_id')
                ->findAll(),
        ];
        return view('admin/page/assign/index', $data);
    }

    public function create()
    {
        // Panggil EmployeeModel untuk mengambil data karyawan
        $employeeModel = new \App\Models\EmployeeModel();

        $data = [
            'reimbursements' => $this->reimbursementModel->findAll(), // Data untuk dropdown reimbursement
            'employees'      => $employeeModel->findAll(), // Ambil semua data karyawan
        ];

        return view('admin/page/assign/create', $data);
    }


    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Save assignment
        $assignmentData = [
            'reimbursement_id' => $this->request->getPost('reimbursement_name'),
            'type'             => $this->request->getPost('type'),
            'description'      => $this->request->getPost('description'),
        ];
        $this->assignmentModel->save($assignmentData);
        $assignmentId = $this->assignmentModel->getInsertID();

        // Save employees with balance
        $employees = $this->request->getPost('employees');
        $balances = $this->request->getPost('balances'); // Associative array: [employee_id => balance]

        if (!empty($employees)) {
            $assignmentEmployeeModel = new \App\Models\AssignmentEmployeeModel();

            $employeeData = [];
            foreach ($employees as $employeeId => $value) {
                $employeeData[] = [
                    'assignment_id' => $assignmentId,
                    'employee_id'   => $employeeId,
                    'balance'       => isset($balances[$employeeId]) ? intval($balances[$employeeId]) : 0,
                ];
            }

            $assignmentEmployeeModel->insertBatch($employeeData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to save assignment.')->withInput();
        }

        return redirect()->to('/admin/assignments')->with('success', 'Assignment saved successfully.');
    }




    public function edit($id)
    {
        $employeeModel = new \App\Models\EmployeeModel();
        $assignmentEmployeeModel = new \App\Models\AssignmentEmployeeModel();

        $data = [
            'assignment' => $this->assignmentModel->find($id), // Ambil data assignment berdasarkan ID
            'reimbursements' => $this->reimbursementModel->findAll(), // Semua data reimbursement
            'employees' => $employeeModel->findAll(), // Semua pegawai untuk opsi penambahan
            'assignedEmployees' => $assignmentEmployeeModel
                ->select('assignment_employees.*, employees.full_name')
                ->join('employees', 'employees.employee_id = assignment_employees.employee_id')
                ->where('assignment_id', $id)
                ->findAll(), // Data pegawai yang sudah terhubung
        ];

        // Format assignedEmployees menjadi array dengan employee_id sebagai key
        $data['assignedEmployees'] = array_column($data['assignedEmployees'], null, 'employee_id');

        return view('admin/page/assign/edit', $data); // Tampilkan halaman edit
    }




    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Update assignment data
        $assignmentData = [
            'reimbursement_id' => $this->request->getPost('reimbursement_name'),
            'type'             => $this->request->getPost('type'),
            'description'      => $this->request->getPost('description'),
        ];
        $this->assignmentModel->update($id, $assignmentData);

        // Update employees with balance
        $employees = $this->request->getPost('employees');
        $balances = $this->request->getPost('balances'); // Associative array: [employee_id => balance]

        $assignmentEmployeeModel = new \App\Models\AssignmentEmployeeModel();

        // Hapus semua data lama untuk assignment ini
        $assignmentEmployeeModel->where('assignment_id', $id)->delete();

        // Simpan pegawai baru
        if (!empty($employees)) {
            $dataToInsert = [];
            foreach ($employees as $employeeId => $value) {
                $dataToInsert[] = [
                    'assignment_id' => $id,
                    'employee_id'   => $employeeId,
                    'balance'       => isset($balances[$employeeId]) ? intval($balances[$employeeId]) : 0,
                ];
            }

            $assignmentEmployeeModel->insertBatch($dataToInsert);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to update assignment.')->withInput();
        }

        return redirect()->to('/admin/assignments')->with('success', 'Assignment updated successfully.');
    }







    public function delete($id)
    {
        // Debug log
        log_message('debug', "Delete called for ID: $id");

        // Cek assignment
        $assignment = $this->assignmentModel->find($id);

        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to('/admin/assignments');
        }

        $this->assignmentModel->delete($id);
        session()->setFlashdata('success', 'Assignment deleted successfully.');

        return redirect()->to('/admin/assignments');
    }


        
}

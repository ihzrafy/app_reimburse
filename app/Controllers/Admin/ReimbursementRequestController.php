<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReimbursementRequestModel;
use App\Models\EmployeeModel;
use App\Models\ReimbursementModel;

class ReimbursementRequestController extends BaseController
{
    protected $requestModel;

    public function __construct()
    {
        $this->requestModel = new ReimbursementRequestModel();
    }

    public function index()
{
    $reimbursementModel = new \App\Models\ReimbursementRequestModel();

    // Ambil input dari filter
    $transactionId = $this->request->getGet('transaction_id');
    $employeeName = $this->request->getGet('employee_name');
    $reimbursementName = $this->request->getGet('reimbursement_name');

    // Query Builder untuk Filter
    $reimbursementModel->select('reimbursement_requests.*, employees.full_name as employee_name, reimbursements.name as reimbursement_name')
                       ->join('employees', 'employees.employee_id = reimbursement_requests.employee', 'left')
                       ->join('reimbursements', 'reimbursements.id = reimbursement_requests.reimbursement_name', 'left')
                       ->orderBy('reimbursement_requests.created_at', 'DESC'); // Urutkan berdasarkan tanggal terbaru

    if (!empty($transactionId)) {
        $reimbursementModel->like('reimbursement_requests.transaction_id', $transactionId);
    }
    if (!empty($employeeName)) {
        $reimbursementModel->like('employees.full_name', $employeeName);
    }
    if (!empty($reimbursementName)) {
        $reimbursementModel->like('reimbursements.name', $reimbursementName);
    }

    // Pagination
    $perPage = 10;
    $requests = $reimbursementModel->paginate($perPage);
    $pager = $reimbursementModel->pager;

    return view('admin/page/request/index', [
        'requests' => $requests,
        'pager' => $pager,
        'transaction_id' => $transactionId,
        'employee_name' => $employeeName,
        'reimbursement_name' => $reimbursementName,
    ]);
}








    public function create()
    {
        $employeeModel = new EmployeeModel();
        $reimbursementModel = new ReimbursementModel();
        // Ambil daftar reimbursement
        $db = \Config\Database::connect();
        $assignedReimbursements = $db->table('reimbursements')
            ->select('id as reimbursement_id, name as reimbursement_name')
            ->get()
            ->getResultArray();

        // Ambil data employee dan reimbursement
        $employees = $employeeModel->findAll();
        $reimbursements = $reimbursementModel->findAll();

        // Generate Transaction ID
        $transactionId = $this->requestModel->generateTransactionId();

        return view('admin/page/request/create', [
            'transaction_id' => $transactionId,
            'employees' => $employees,
            'reimbursements' => $reimbursements,
            'assignedReimbursements' => $assignedReimbursements,
        ]);
    }


    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi

        // Ambil Transaction ID dari model (untuk memastikan selalu unik)
        $transactionId = $this->requestModel->generateTransactionId();

        // Simpan data request
        $data = [
            'transaction_id'    => $transactionId,
            'employee'          => $this->request->getPost('employee'),
            'reimbursement_name' => (int) $this->request->getPost('reimbursement_name'),
            'effective_date'    => $this->request->getPost('effective_date'),
            'description'       => $this->request->getPost('description'),
            'status'            => 'Pending',
        ];

        if ($file = $this->request->getFile('attachment')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/attachments', $newName);
                $data['attachment'] = $newName;
            }
        }

        $this->requestModel->insert($data);

        // Simpan data benefits
        $benefitNames = $this->request->getPost('benefit_name');
        $requestAmounts = $this->request->getPost('request_amount');
        $paidAmounts = $this->request->getPost('paid_amount');
        $descriptions = $this->request->getPost('benefit_description');

        $benefits = [];
        foreach ($benefitNames as $index => $benefitName) {
            $benefits[] = [
                'transaction_id'    => $transactionId,
                'benefit_name'      => $benefitName,
                'request_amount'    => $requestAmounts[$index],
                'paid_amount'       => $paidAmounts[$index],
                'benefit_description' => $descriptions[$index],
            ];
        }

        $benefitModel = new \App\Models\BenefitModel();
        $benefitModel->insertBatch($benefits);

        $db->transComplete(); // Selesaikan transaksi

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create reimbursement request.');
        }

        return redirect()->to('/admin/request')->with('success', 'Request created successfully.');
    }



    public function edit($id)
    {
        $request = $this->requestModel->find($id);
        return view('admin/page/request/edit', ['request' => $request]);
    }

    public function update($id)
    {
        $data = [
            'employee' => $this->request->getPost('employee'),
            'reimbursement_name' => $this->request->getPost('reimbursement_name'),
            'effective_date' => $this->request->getPost('effective_date'),
            'description' => $this->request->getPost('description'),
        ];

        if ($file = $this->request->getFile('attachment')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/attachments', $newName);
                $data['attachment'] = $newName;
            }
        }

        $this->requestModel->update($id, $data);
        return redirect()->to('/admin/request')->with('success', 'Request updated successfully.');
    }

    // Contoh pada aksi approve
    public function approve($transactionId)
    {
        $this->requestModel->where('transaction_id', $transactionId)->set(['status' => 'Approved'])->update();

        session()->setFlashdata('success', 'Request has been approved successfully.');

        return redirect()->to('/admin/request');
    }

    // Contoh pada aksi reject
    public function reject($transactionId)
    {
        $this->requestModel->where('transaction_id', $transactionId)->set(['status' => 'Rejected'])->update();

        session()->setFlashdata('success', 'Request has been rejected successfully.');

        return redirect()->to('/admin/request');
    }

    // Contoh pada aksi delete
    public function delete($transactionId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $benefitModel = new \App\Models\BenefitModel();
        $benefitModel->where('transaction_id', $transactionId)->delete();

        $this->requestModel->where('transaction_id', $transactionId)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Failed to delete the request.');
        } else {
            session()->setFlashdata('success', 'Request has been deleted successfully.');
        }

        return redirect()->to('/admin/request');
    }



    public function pending($id)
    {
        $this->requestModel->update($id, ['status' => 'Pending']);
        return redirect()->to('/admin/request')->with('success', 'Request marked as pending.');
    }

    public function exportPdf()
    {
        // Ambil data request reimbursement
        $db = \Config\Database::connect();
        $builder = $db->table('reimbursement_requests');
        $requests = $builder->select('reimbursement_requests.*, employees.full_name as employee_name, reimbursements.name as reimbursement_name')
                            ->join('employees', 'employees.employee_id = reimbursement_requests.employee', 'left')
                            ->join('reimbursements', 'reimbursements.id = reimbursement_requests.reimbursement_name', 'left')
                            ->orderBy('reimbursement_requests.created_at', 'DESC')
                            ->get()
                            ->getResultArray();

        // Load Dompdf
        $dompdf = new \Dompdf\Dompdf();

        // Load view untuk PDF
        $html = view('admin/page/request/pdf', ['requests' => $requests]);

        // Render HTML ke PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Atur ukuran kertas dan orientasi
        $dompdf->render();

        // Unduh PDF
        $dompdf->stream('All_Reimbursement_Requests.pdf', ["Attachment" => true]);
    }

}

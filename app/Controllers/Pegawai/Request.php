<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use App\Models\ReimbursementRequestModel;
use App\Models\ReimbursementModel;
use App\Models\BenefitModel;

class Request extends BaseController
{
    protected $requestModel;

    public function __construct()
    {
        $this->requestModel = new ReimbursementRequestModel();
    }

    public function index()
    {
        $reimbursementModel = new \App\Models\ReimbursementRequestModel();
        $currentPegawaiId = session()->get('employee_id'); // ID pegawai dari session

        // Ambil input pencarian dari form
        $search = $this->request->getGet('search');

        // Query untuk mengambil data dengan pencarian
        $reimbursementModel->select('reimbursement_requests.*, reimbursements.name as reimbursement_name')
                        ->join('reimbursements', 'reimbursements.id = reimbursement_requests.reimbursement_name', 'left')
                        ->where('reimbursement_requests.employee', $currentPegawaiId)
                        ->orderBy('reimbursement_requests.created_at', 'DESC');

        // Jika ada input pencarian, tambahkan kondisi
        if (!empty($search)) {
            $reimbursementModel->groupStart()
                            ->like('reimbursement_requests.transaction_id', $search)
                            ->orLike('reimbursements.name', $search)
                            ->orLike('reimbursement_requests.description', $search)
                            ->groupEnd();
        }

        // Pagination
        $perPage = 10;
        $requests = $reimbursementModel->paginate($perPage);
        $pager = $reimbursementModel->pager;

        return view('pegawai/page/reimbursement/index', [
            'requests' => $requests,
            'pager' => $pager,
            'search' => $search, // Kirim input pencarian ke view
        ]);
    }






    public function create()
    {
        $currentPegawaiId = session()->get('employee_id'); // Ambil employee_id dari session pegawai login

        // Ambil data reimbursement yang diassign ke pegawai login
        $db = \Config\Database::connect();
        $query = $db->table('assignment_employees')
                    ->select('assignment_employees.balance, reimbursements.name as reimbursement_name, reimbursements.id as reimbursement_id')
                    ->join('assignments', 'assignments.id = assignment_employees.assignment_id', 'left')
                    ->join('reimbursements', 'reimbursements.id = assignments.reimbursement_id', 'left')
                    ->where('assignment_employees.employee_id', $currentPegawaiId)
                    ->get();

        $assignedReimbursements = $query->getResultArray();

        // Data Pegawai
        $currentPegawai = [
            'pegawai_id' => $currentPegawaiId,
            'full_name'  => session()->get('full_name'),
        ];

        return view('pegawai/page/reimbursement/create', [
            'transaction_id' => $this->requestModel->generateTransactionId(),
            'assignedReimbursements' => $assignedReimbursements,
            'currentPegawai' => $currentPegawai,
        ]);
    }


    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai transaksi untuk data konsisten

        // Ambil Transaction ID
        $transactionId = $this->requestModel->generateTransactionId();

        // Simpan data request
        $data = [
            'transaction_id'    => $transactionId,
            'employee'          => session()->get('employee_id'), // Ambil employee_id dari session
            'reimbursement_name' => $this->request->getPost('reimbursement_name'),
            'effective_date'    => $this->request->getPost('effective_date'),
            'description'       => $this->request->getPost('description'),
            'status'            => 'Pending',
        ];

        // Upload file jika ada
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

        $benefitModel = new BenefitModel();
        $benefitModel->insertBatch($benefits);

        $db->transComplete(); // Selesaikan transaksi

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create reimbursement request.');
        }

        return redirect()->to('/pegawai/request')->with('success', 'Reimbursement request created successfully.');
    }

    public function edit($id)
    {
        // Cek apakah request ini milik pegawai yang sedang login
        $currentEmployeeId = session()->get('employee_id');
        $request = $this->requestModel
            ->where('employee', $currentEmployeeId)
            ->find($id);

        if (!$request) {
            return redirect()->to('/pegawai/request')->with('error', 'Reimbursement request not found.');
        }

        // Ambil reimbursement yang diassign ke pegawai
        $db = \Config\Database::connect();
        $query = $db->table('assignment_employees')
                    ->select('assignment_employees.balance, reimbursements.name as reimbursement_name, reimbursements.id as reimbursement_id')
                    ->join('assignments', 'assignments.id = assignment_employees.assignment_id', 'left')
                    ->join('reimbursements', 'reimbursements.id = assignments.reimbursement_id', 'left')
                    ->where('assignment_employees.employee_id', $currentEmployeeId)
                    ->get();

        $assignedReimbursements = $query->getResultArray();

        return view('pegawai/page/reimbursement/edit', [
            'request' => $request,
            'assignedReimbursements' => $assignedReimbursements,
        ]);
    }



    public function update($id)
    {
        // Cek apakah request ini milik pegawai yang sedang login
        $currentEmployeeId = session()->get('employee_id');
        $request = $this->requestModel
            ->where('employee', $currentEmployeeId)
            ->find($id);

        if (!$request) {
            return redirect()->to('/pegawai/request')->with('error', 'Reimbursement request not found.');
        }

        // Data yang akan diupdate
        $data = [
            'reimbursement_name' => $this->request->getPost('reimbursement_name'),
            'effective_date'     => $this->request->getPost('effective_date'),
            'description'        => $this->request->getPost('description'),
        ];

        // Upload file jika ada
        if ($file = $this->request->getFile('attachment')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/attachments', $newName);
                $data['attachment'] = $newName;

                // Hapus file lama jika ada
                if (!empty($request['attachment']) && file_exists('uploads/attachments/' . $request['attachment'])) {
                    unlink('uploads/attachments/' . $request['attachment']);
                }
            }
        }

        $this->requestModel->update($id, $data);
        return redirect()->to('/pegawai/request')->with('success', 'Reimbursement request updated successfully.');
    }


    public function delete($id)
    {
        // Cek apakah request ini milik pegawai yang sedang login
        $currentEmployeeId = session()->get('employee_id');
        $request = $this->requestModel
            ->where('employee', $currentEmployeeId)
            ->find($id);

        if (!$request) {
            return redirect()->to('/pegawai/request')->with('error', 'Reimbursement request not found.');
        }

        // Hapus data
        $this->requestModel->delete($id);

        return redirect()->to('/pegawai/request')->with('success', 'Reimbursement request deleted successfully.');
    }
    public function exportPdf()
    {
        // Ambil data pegawai dari session
        $currentPegawaiId = session()->get('employee_id');

        // Ambil request reimbursement milik pegawai login
        $reimbursementModel = new \App\Models\ReimbursementRequestModel();
        $requests = $reimbursementModel
            ->select('reimbursement_requests.*, reimbursements.name as reimbursement_name')
            ->join('reimbursements', 'reimbursements.id = reimbursement_requests.reimbursement_name', 'left')
            ->where('reimbursement_requests.employee', $currentPegawaiId)
            ->orderBy('reimbursement_requests.created_at', 'DESC')
            ->findAll();

        // Load Dompdf
        $dompdf = new \Dompdf\Dompdf();

        // Load view untuk PDF
        $html = view('pegawai/page/reimbursement/pdf', ['requests' => $requests]);

        // Render HTML ke PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Atur ukuran kertas dan orientasi
        $dompdf->render();

        // Unduh PDF
        $dompdf->stream('Reimbursement_Requests.pdf', ["Attachment" => true]);
    }


}

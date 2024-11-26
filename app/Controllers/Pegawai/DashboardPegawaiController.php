<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;

class DashboardPegawaiController extends BaseController
{
    public function index()
    {
        // Ambil ID pegawai dari session
        $currentPegawaiId = session()->get('employee_id');

        // Koneksi ke database
        $db = \Config\Database::connect();

        // Total Requests (Semua Permintaan)
        $myTotalRequests = $db->table('reimbursement_requests')
            ->where('employee', $currentPegawaiId)
            ->countAllResults();

        // Approved Requests
        $myApprovedRequests = $db->table('reimbursement_requests')
            ->where('employee', $currentPegawaiId)
            ->where('status', 'Approved')
            ->countAllResults();

        // Pending Requests
        $myPendingRequests = $db->table('reimbursement_requests')
            ->where('employee', $currentPegawaiId)
            ->where('status', 'Pending')
            ->countAllResults();

            // Rejected Requests
        $myRejectedRequests = $db->table('reimbursement_requests')
        ->where('employee', $currentPegawaiId)
        ->where('status', 'Rejected')
        ->countAllResults();


        // Requests by Categories (Jumlah Permintaan Berdasarkan Kategori)
        $categoriesQuery = $db->table('reimbursement_requests')
            ->select('reimbursements.name AS category, COUNT(reimbursement_requests.id) AS count')
            ->join('reimbursements', 'reimbursements.id = reimbursement_requests.reimbursement_name', 'left')
            ->where('reimbursement_requests.employee', $currentPegawaiId)
            ->groupBy('reimbursement_requests.reimbursement_name')
            ->get()
            ->getResult();

        // Siapkan data kategori untuk grafik
        $categoryLabels = [];
        $categoryData = [];
        foreach ($categoriesQuery as $category) {
            $categoryLabels[] = $category->category;
            $categoryData[] = $category->count;
        }

        // Requests by Status (Jumlah Permintaan Berdasarkan Status)
        $statusesQuery = $db->table('reimbursement_requests')
            ->select('status, COUNT(id) AS count')
            ->where('employee', $currentPegawaiId)
            ->groupBy('status')
            ->get()
            ->getResult();

        // Siapkan data status untuk grafik
        $statusLabels = [];
        $statusData = [];
        foreach ($statusesQuery as $status) {
            $statusLabels[] = ucfirst($status->status); // Capitalize
            $statusData[] = $status->count;
        }

        // Kirim data ke view
        return view('pegawai/dashboard', [
            'myTotalRequests'    => $myTotalRequests,
            'myApprovedRequests' => $myApprovedRequests,
            'myPendingRequests'  => $myPendingRequests,
            'myRejectedRequests' => $myRejectedRequests,
            'categoryLabels'     => $categoryLabels,
            'categoryData'       => $categoryData,
            'statusLabels'       => $statusLabels,
            'statusData'         => $statusData,
        ]);
    }
}

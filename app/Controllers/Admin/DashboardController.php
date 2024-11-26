<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
{
    $db = \Config\Database::connect();

    // Total Requests
    $totalRequests = $db->table('reimbursement_requests')->countAll();

    // Approved, Pending, Rejected
    $approvedRequests = $db->table('reimbursement_requests')->where('status', 'Approved')->countAllResults();
    $pendingRequests = $db->table('reimbursement_requests')->where('status', 'Pending')->countAllResults();
    $rejectedRequests = $db->table('reimbursement_requests')->where('status', 'Rejected')->countAllResults();

    // Requests by Status
    $statusData = [
        'labels' => ['Approved', 'Pending', 'Rejected'],
        'values' => [$approvedRequests, $pendingRequests, $rejectedRequests]
    ];

    // Requests Over Time
    $timeResults = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                               FROM reimbursement_requests GROUP BY month ORDER BY month ASC")->getResultArray();
    $timeLabels = array_column($timeResults, 'month');
    $timeData = array_column($timeResults, 'count');

    // Top Employees
    $employeeResults = $db->query("SELECT employees.full_name, COUNT(*) as count 
                                   FROM reimbursement_requests 
                                   JOIN employees ON employees.employee_id = reimbursement_requests.employee 
                                   GROUP BY employees.full_name ORDER BY count DESC LIMIT 5")->getResultArray();
    $employeeLabels = array_column($employeeResults, 'full_name');
    $employeeData = array_column($employeeResults, 'count');

    // Requests by Categories
    $categoryResults = $db->query("SELECT reimbursements.name, COUNT(*) as count 
                                   FROM reimbursement_requests 
                                   JOIN reimbursements ON reimbursements.id = reimbursement_requests.reimbursement_name 
                                   GROUP BY reimbursements.name ORDER BY count DESC")->getResultArray();
    $categoryLabels = array_column($categoryResults, 'name');
    $categoryData = array_column($categoryResults, 'count');

    return view('admin/dashboard', compact(
        'totalRequests', 'approvedRequests', 'pendingRequests', 'rejectedRequests', 
        'statusData', 'timeLabels', 'timeData', 
        'employeeLabels', 'employeeData', 
        'categoryLabels', 'categoryData'
    ));
}


}

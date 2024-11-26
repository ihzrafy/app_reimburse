<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route default ke halaman login
$routes->get('/', 'Auth::login'); // Arahkan ke halaman login

// Route untuk login dan logout
$routes->get('login', 'Auth::login'); // Menampilkan halaman login
$routes->post('auth/processLogin', 'Auth::processLogin'); // Memproses login
$routes->get('logout', 'Auth::logout'); // Logout

$routes->get('/register', 'Auth::register');
$routes->post('/auth/processRegister', 'Auth::processRegister');


// Route grup untuk admin
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {

    $routes->post('profile/update-picture', 'Admin\ProfileAdmin::updatePicture');
    $routes->post('profile/remove-picture', 'Admin\ProfileAdmin::removePicture');

    $routes->get('dashboard', 'Admin\DashboardController::index'); // Dashboard Admin

    // Route grup untuk reimbursement (Admin)
    $routes->group('reimbursement', function ($routes) {
        $routes->get('/', 'Admin\Reimbursement::index');
        $routes->get('create', 'Admin\Reimbursement::create');
        $routes->post('store', 'Admin\Reimbursement::store');
        $routes->get('edit/(:num)', 'Admin\Reimbursement::edit/$1');
        $routes->post('update/(:num)', 'Admin\Reimbursement::update/$1');
        $routes->get('delete/(:num)', 'Admin\Reimbursement::delete/$1');
        $routes->get('new_policy', 'Admin\Reimbursement::newPolicy'); // Rute untuk New Policy
        $routes->get('export', 'Admin\Reimbursement::export'); // Export data
        $routes->post('import', 'Admin\Reimbursement::import'); // Import data
        $routes->get('details/(:num)', 'Admin\Reimbursement::details/$1');
        $routes->get('assign/(:num)', 'Admin\Reimbursement::viewEmployees/$1');


        
    });

    $routes->group('request', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'ReimbursementRequestController::index'); // List requests
        $routes->get('create', 'ReimbursementRequestController::create'); // Form create request
        $routes->post('store', 'ReimbursementRequestController::store'); // Store request
        $routes->get('edit/(:segment)', 'ReimbursementRequestController::edit/$1'); // Form edit request
        $routes->post('update/(:segment)', 'ReimbursementRequestController::update/$1'); // Update request
        $routes->get('delete/(:segment)', 'ReimbursementRequestController::delete/$1'); // Delete request
        $routes->get('approve/(:segment)', 'ReimbursementRequestController::approve/$1'); // Approve request
        $routes->get('reject/(:segment)', 'ReimbursementRequestController::reject/$1'); // Reject request
        $routes->get('pending/(:segment)', 'ReimbursementRequestController::pending/$1'); // Mark as pending
        $routes->get('export-pdf', 'ReimbursementRequestController::exportPdf'); // Unduh semua request reimbursement sebagai PDF

    });
    

    $routes->group('assignments', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
        $routes->get('/', 'AssignmentController::index'); // List assignments
        $routes->get('create', 'AssignmentController::create'); // Form create
        $routes->post('store', 'AssignmentController::store'); // Store data
        $routes->get('edit/(:num)', 'AssignmentController::edit/$1'); // Edit data
        $routes->post('update/(:num)', 'AssignmentController::update/$1'); // Update data
        $routes->get('delete/(:num)', 'AssignmentController::delete/$1'); // Delete data
    });
    
    
    

    // Route grup untuk payroll (Admin)
    $routes->group('payroll', function ($routes) {
        $routes->get('/', 'Admin\Payroll::index'); // Daftar payroll
        $routes->get('download-excel', 'Admin\Payroll::downloadExcel'); // Download Excel dengan filter
    });
});

// Routes untuk pegawai
$routes->group('pegawai', ['filter' => 'auth:pegawai'], function ($routes) {
    $routes->get('dashboard', 'Pegawai\DashboardPegawaiController::index'); // Dashboard Pegawai

    // Routes untuk request reimbursement
    $routes->get('request', 'Pegawai\Request::index'); // Daftar request reimbursement pegawai
    $routes->get('request/create', 'Pegawai\Request::create'); // Form untuk membuat request reimbursement
    $routes->post('request/store', 'Pegawai\Request::store'); // Simpan request reimbursement pegawai
    $routes->get('request/edit/(:num)', 'Pegawai\Request::edit/$1'); // Form untuk mengedit request reimbursement
    $routes->post('request/update/(:num)', 'Pegawai\Request::update/$1'); // Update request reimbursement
    $routes->get('request/delete/(:num)', 'Pegawai\Request::delete/$1'); // Hapus request reimbursement
    $routes->get('request/export-pdf', 'Pegawai\Request::exportPdf'); // Unduh semua request reimbursement pegawai sebagai PDF
    $routes->post('profile/update-picture', 'Pegawai\ProfilePegawai::updatePicture');
    $routes->post('profile/remove-picture', 'Pegawai\ProfilePegawai::removePicture');
    
    
});





<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<style>
    table.table-bordered {
        border-collapse: collapse; /* Agar garis antar sel menyatu */
    }
    table.table-bordered th,
    table.table-bordered td {
        border: 1px solid #dee2e6; /* Warna garis */
    }
    table.table-bordered th,
    table.table-bordered td {
        padding: 8px;
    }
  
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    .pagination .page-link {
        color: #007bff;
    }
    .pagination .page-link:hover {
        background-color: #f8f9fa;
    }




</style>


<div class="pagetitle">
    <h1>Reimbursement Request</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
            <li class="breadcrumb-item active">Reimbursement Request</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Filter Period</h5>

            <!-- Filter Form -->
            <form method="get" action="">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="transactionId" class="form-label">Transaction ID</label>
                        <input type="text" id="transactionId" name="transaction_id" class="form-control" value="<?= esc($transaction_id ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <input type="text" id="employeeName" name="employee_name" class="form-control" value="<?= esc($employee_name ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="reimbursementName" class="form-label">Reimbursement Name</label>
                        <input type="text" id="reimbursementName" name="reimbursement_name" class="form-control" value="<?= esc($reimbursement_name ?? '') ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="/admin/request" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="mb-3">
                <!-- Button to navigate to the Create page -->
                <a href="/admin/request/create" class="btn btn-outline-primary">
                    Create Reimbursement Request
                </a>
                <a href="/admin/request/export-pdf" class="btn btn-outline-danger">
                    Download PDF
                </a>

            </div>

            <!-- Reimbursement Request Table -->
            <div class="table-responsive">
                <table id="reimbursementRequestTable" class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Transaction ID</th>
                            <th>Employee</th>
                            <th>Reimbursement Name</th>
                            <th>Effective Date</th>
                            <th>Description</th>
                            <th>Attachment file</th> <!-- Kolom baru -->
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $index => $request): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($request['transaction_id']) ?></td>
                                <td><?= esc($request['employee_name']) ?></td>
                                <td><?= esc($request['reimbursement_name']) ?></td>
                                <td><?= esc($request['effective_date']) ?></td>
                                <td><?= esc($request['description']) ?></td>
                                <td class="text-center">
                                    <?php if (!empty($request['attachment'])): ?>
                                        <a href="<?= base_url('uploads/attachments/' . esc($request['attachment'])) ?>" 
                                        target="_blank" 
                                        class="btn btn-sm btn-info">
                                            View
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No Attachment</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($request['status'] === 'Pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($request['status'] === 'Approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif ($request['status'] === 'Rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($request['status'] === 'Pending'): ?>
                                        <button class="btn btn-success btn-sm swal-approve" 
                                                data-url="/admin/request/approve/<?= esc($request['transaction_id']) ?>">
                                            Approve
                                        </button>
                                        <button class="btn btn-danger btn-sm swal-reject" 
                                                data-url="/admin/request/reject/<?= esc($request['transaction_id']) ?>">
                                            Reject
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-danger swal-delete" 
                                            data-url="/admin/request/delete/<?= esc($request['transaction_id']) ?>" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination justify-content-end">
                <?= $pager->links('default', 'bootstrap_pagination') ?>
            </div>




        </div>
    </div>
</section>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= session()->getFlashdata('success') ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php elseif (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

<script>
    $(document).ready(function () {
        let benefitCount = 1; // Counter untuk baris benefit

        // Event listener untuk tombol Add Benefit
        $('#addBenefitBtn').click(function () {
            benefitCount++; // Increment counter

            // Tambahkan baris baru ke tabel benefits
            $('#benefitsTable').append(`
                <tr>
                    <td>${benefitCount}</td>
                    <td>
                        <select name="benefit_name[]" class="form-select" required>
                            <option value="">-- Select Benefit --</option>
                            <option value="Biaya Transportasi">Biaya Transportasi</option>
                            <option value="Biaya Medis">Biaya Medis</option>
                        </select>
                    </td>
                    <td><input type="number" name="request_amount[]" class="form-control" required></td>
                    <td><input type="number" name="paid_amount[]" class="form-control" required></td>
                    <td><input type="text" name="benefit_description[]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-benefit">❌</button></td>
                </tr>
            `);
        });

        // Event listener untuk tombol Remove Benefit
        $(document).on('click', '.remove-benefit', function () {
            $(this).closest('tr').remove();
        });

        // SweetAlert untuk Approve
        $(document).on('click', '.swal-approve', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to approve this request.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // SweetAlert untuk Reject
        $(document).on('click', '.swal-reject', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to reject this request.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // SweetAlert untuk Delete
        $(document).on('click', '.swal-delete', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

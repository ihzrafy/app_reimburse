<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>My Reimbursement Requests</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/pegawai/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Reimbursement Requests</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">My Requests</h5>

            <div class="mb-3">
                <!-- Button to navigate to the Create page -->
                <a href="/pegawai/request/create" class="btn btn-outline-primary">
                    Create Reimbursement Request
                </a>
                <a href="/pegawai/request/export-pdf" class="btn btn-outline-danger">
                    Download PDF
                </a>

            </div>
            <div class="mb-3">
                <form method="get" action="">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                placeholder="Search Transaction ID, Name, or Description" 
                                value="<?= esc($search ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/pegawai/request" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>


            <!-- Reimbursement Request Table -->
            <table id="reimbursementRequestTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Transaction ID</th>
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
                            <!-- Edit button -->
                            <?php if ($request['status'] === 'Pending'): ?>
                                <a href="/pegawai/request/edit/<?= $request['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-danger swal-delete" 
                                        data-url="/pegawai/request/delete/<?= esc($request['id']) ?>" title="Delete">
                                    <i class="bi bi-trash"></i> 
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Tambahkan Pagination -->
        <div class="pagination justify-content-end">
            <?= $pager->links('default', 'bootstrap_pagination') ?>
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

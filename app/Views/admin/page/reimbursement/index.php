<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Reimbursement List</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Reimbursements</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row mb-4">
        <!-- Button Group for Actions -->
        <div class="col-md-4">
            <h5>Assign/Update/Request Reimbursement</h5>
            <button class="btn btn-primary" onclick="window.location.href='/admin/assignments'">Assign or Update</button>
            <button class="btn btn-primary" onclick="window.location.href='/admin/request'">Request</button>
        </div>
        <div class="col-md-4">
            <h5>Update Reimbursement Balance via Excel</h5>
            <div class="d-flex gap-2">
                <!-- Export Button -->
                <button class="btn btn-primary" onclick="window.location.href='/admin/reimbursement/export'">Export</button>

                <!-- Import Button -->
                <form id="importForm" action="/admin/reimbursement/import" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="file" name="file" accept=".xlsx,.xls" id="fileInput" class="d-none" 
                        onchange="document.getElementById('importForm').submit();" required>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click();">Import</button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <h5>Create or View Setting Reimbursement</h5>
            <button class="btn btn-primary" onclick="window.location.href='/admin/reimbursement/new_policy'">New</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Reimbursements Table</h5>
            <div class="table-responsive">
                <table id="reimbursementsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Reimbursement Name</th>
                            <th>Reimbursement Limit</th>
                            <th>Expired In</th>
                            <th>Effective Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reimbursements as $index => $reimbursement): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($reimbursement['name']) ?></td>
                        <td><?= $reimbursement['limit'] === 'UNLIMITED' ? 'UNLIMITED' : 'Rp ' . number_format($reimbursement['limit'], 0, ',', '.') ?></td>
                        <td><?= esc($reimbursement['expired_in']) ?></td>
                        <td><?= esc($reimbursement['effective_date']) ?></td>
                        <td>
                            <a href="/admin/reimbursement/edit/<?= $reimbursement['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-danger btn-sm swal-delete" 
                                    data-url="/admin/reimbursement/delete/<?= $reimbursement['id'] ?>" title="Delete">
                                <i class="bi bi-x"></i>
                            </button>
                            <a href="/admin/reimbursement/details/<?= $reimbursement['id'] ?>" class="btn btn-secondary btn-sm" title="Details">
                                <i class="bi bi-list"></i>
                            </a>
                            <a href="/admin/reimbursement/assign/<?= $reimbursement['id'] ?>" class="btn btn-primary btn-sm" title="Assign">
                                <i class="bi bi-person"></i> 
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert Messages -->
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize DataTable
        

        // SweetAlert for Delete Confirmation
        $(document).on('click', '.swal-delete', function () {
            const deleteUrl = $(this).data('url');

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
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

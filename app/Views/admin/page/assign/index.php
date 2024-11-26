<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Assign or Update Reimbursement</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
            <li class="breadcrumb-item active">Assign or Update</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Reimbursement Assignments</h5>
                <a href="/admin/assignments/create" class="btn btn-primary">New</a>
            </div>

            <!-- Data Table -->
            <table id="assignUpdateTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Transaction ID</th>
                        <th>Type</th>
                        <th>Reimbursement Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $index => $assignment): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($assignment['id']) ?></td>
                            <td><?= esc($assignment['type']) ?></td>
                            <td><?= esc($assignment['reimbursement_name']) ?></td>
                            <td><?= esc($assignment['description']) ?></td>
                            <td>
                                <a href="/admin/assignments/edit/<?= $assignment['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger swal-delete" 
                                    data-url="/admin/assignments/delete/<?= esc($assignment['id']) ?>" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>


                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                    // Redirect to the delete URL
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>
<div class="pagetitle">
    <h1>Employee Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/pegawai/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Total Requests</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div class="ps-3">
                            <h6><?= $myTotalRequests ?></h6>
                            <span class="text-muted small">All time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Approved Requests</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6><?= $myApprovedRequests ?></h6>
                            <span class="text-muted small">Approved so far</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Pending Requests</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="ps-3">
                            <h6><?= $myPendingRequests ?></h6>
                            <span class="text-muted small">Awaiting action</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Rejected Requests</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6><?= $myRejectedRequests ?></h6>
                            <span class="text-muted small">Rejected so far</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Requests by Categories</h5>
                    <canvas id="categoriesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Requests by Status</h5>
                    <canvas id="statusChart"></canvas>
                </div>
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

<!-- Chart Scripts -->
<script>
    const categoryLabels = <?= json_encode($categoryLabels) ?>;
    const categoryData = <?= json_encode($categoryData) ?>;
    const statusLabels = <?= json_encode($statusLabels) ?>;
    const statusData = <?= json_encode($statusData) ?>;

    // Chart for Categories
    new Chart(document.getElementById('categoriesChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545']
            }]
        }
    });

    // Chart for Status
    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
            }]
        }
    });
</script>
<?= $this->endSection() ?>

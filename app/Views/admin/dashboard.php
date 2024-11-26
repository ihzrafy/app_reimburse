<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<style>
    .card canvas {
        max-height: 400px; /* Menyeragamkan tinggi grafik */
        max-width: 100%; /* Grafik mengambil lebar penuh */
    }
</style>

<div class="pagetitle">
    <h1>Admin Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
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
                            <h6><?= $totalRequests ?></h6>
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
                            <h6><?= $approvedRequests ?></h6>
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
                            <h6><?= $pendingRequests ?></h6>
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
                            <h6><?= $rejectedRequests ?></h6>
                            <span class="text-muted small">Not approved</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Employees with Most Requests</h5>
                    <canvas id="topEmployeesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Requests by Categories</h5>
                    <canvas id="categoriesChart"></canvas>
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
    const employeeLabels = <?= json_encode($employeeLabels) ?>;
    const employeeData = <?= json_encode($employeeData) ?>;
    const categoryLabels = <?= json_encode($categoryLabels) ?>;
    const categoryData = <?= json_encode($categoryData) ?>;

    // Chart for Top Employees
    new Chart(document.getElementById('topEmployeesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: employeeLabels,
            datasets: [{
                label: 'Requests',
                data: employeeData,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Memastikan grafik menyesuaikan kontainer
            indexAxis: 'y', // Grafik horizontal
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart for Requests by Categories
    new Chart(document.getElementById('categoriesChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Memastikan grafik menyesuaikan kontainer
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>

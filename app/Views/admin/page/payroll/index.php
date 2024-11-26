<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Payroll Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Payroll</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Reimbursement Summary Report</h5>

            <!-- Filter Form -->
            <form action="/admin/payroll" method="get" class="mb-3">
                <div class="row">
                    <!-- Filter by Year -->
                    <div class="col-md-4">
                        <label for="year" class="form-label">Select Year</label>
                        <select name="year" id="year" class="form-select">
                            <option value="">-- All Years --</option>
                            <?php for ($y = date('Y'); $y >= (date('Y') - 5); $y--): ?>
                                <option value="<?= $y ?>" <?= isset($year) && $year == $y ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Filter by Month -->
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <select name="month" id="month" class="form-select">
                            <option value="">-- All Months --</option>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= isset($month) && $month == $m ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Button Download Excel -->
            <div class="mb-3">
                <a href="/admin/payroll/download-excel?year=<?= $year ?? '' ?>&month=<?= $month ?? '' ?>" 
                   class="btn btn-success">
                    <i class="bi bi-download"></i> Download Excel
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">Employee ID</th>
                            <th rowspan="2">Full Name</th>
                            <th rowspan="2">Reimbursement Name</th>
                            <th rowspan="2">Balance</th>
                            <th colspan="12">Period Claims (Monthly)</th>
                        </tr>
                        <tr>
                        <?php
                            $displayYear = $year ?? date('Y'); // Gunakan tahun dari filter atau tahun saat ini
                            ?>
                            <?php for ($month = 1; $month <= 12; $month++): ?>
                                <th><?= date('M', mktime(0, 0, 0, $month, 1)) . " " . $displayYear ?></th>
                            <?php endfor; ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?= esc($employee['employee_id']) ?></td>
                                    <td><?= esc($employee['full_name']) ?></td>
                                    <td><?= esc($employee['reimbursement_name']) ?></td>
                                    <td>Rp <?= number_format($employee['balance'], 0, ',', '.') ?></td>
                                    <?php for ($month = 1; $month <= 12; $month++): ?>
                                        <td>
                                            <?= isset($claimData[$employee['employee_id']][$month]) 
                                                ? number_format($claimData[$employee['employee_id']][$month], 0, ',', '.') 
                                                : 0 ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="16" class="text-center">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

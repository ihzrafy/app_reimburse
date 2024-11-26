<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Employee Balances for <?= esc($reimbursement['name']) ?></h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
            <li class="breadcrumb-item active">Employee Balances</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Employees</h5>
            <table id="employeeBalancesTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $index => $employee): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($employee['employee_id']) ?></td>
                            <td><?= esc($employee['full_name']) ?></td>
                            <td>Rp <?= number_format($employee['balance'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>

</script>

<?= $this->endSection() ?>

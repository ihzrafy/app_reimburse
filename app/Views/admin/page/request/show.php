<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Reimbursement Details</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Details</h5>

            <p><strong>Transaction ID:</strong> <?= esc($request['transaction_id']) ?></p>
            <p><strong>Employee:</strong> <?= esc($request['employee']) ?></p>
            <p><strong>Description:</strong> <?= esc($request['description']) ?></p>

            <h5 class="mt-4">Benefits</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Benefit Name</th>
                        <th>Request Amount</th>
                        <th>Paid Amount</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($benefits as $index => $benefit): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($benefit['benefit_name']) ?></td>
                            <td><?= esc($benefit['request_amount']) ?></td>
                            <td><?= esc($benefit['paid_amount']) ?></td>
                            <td><?= esc($benefit['benefit_description']) ?></td>
                            <td>
                                <form method="post" action="/admin/reimbursement/request/delete-benefit/<?= $benefit['id'] ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

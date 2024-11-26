<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Reimbursement Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= esc($reimbursement['name']) ?> Details</h5>
            <p><strong>Limit:</strong> <?= $reimbursement['limit'] === 'UNLIMITED' ? 'UNLIMITED' : 'Rp ' . number_format($reimbursement['limit'], 0, ',', '.') ?></p>
            <p><strong>Expired In:</strong> <?= esc($reimbursement['expired_in']) ?></p>
            <p><strong>Effective Date:</strong> <?= esc($reimbursement['effective_date']) ?></p>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

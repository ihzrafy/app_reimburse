<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Edit Reimbursement</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
            <li class="breadcrumb-item active">Edit Reimbursement</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Reimbursement</h5>

            <form method="post" action="/admin/reimbursement/update/<?= $reimbursement['id'] ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="reimbursementName" class="form-label">Reimbursement Name</label>
                    <input type="text" class="form-control" id="reimbursementName" name="name" value="<?= esc($reimbursement['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="reimbursementLimit" class="form-label">Reimbursement Limit</label>
                    <input type="text" class="form-control" id="reimbursementLimit" name="limit" value="<?= esc($reimbursement['limit']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="expiredIn" class="form-label">Expired In</label>
                    <input type="text" class="form-control" id="expiredIn" name="expired_in" value="<?= esc($reimbursement['expired_in']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="effectiveDate" class="form-label">Effective Date</label>
                    <input type="date" class="form-control" id="effectiveDate" name="effective_date" value="<?= esc($reimbursement['effective_date']) ?>" required>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="/admin/reimbursement" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

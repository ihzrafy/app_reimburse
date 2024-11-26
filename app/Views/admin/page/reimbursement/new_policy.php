<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Create Reimbursement</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
            <li class="breadcrumb-item active">Create Reimbursement</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create New Reimbursement</h5>

            <!-- Form for creating a new reimbursement -->
            <form method="post" action="/admin/reimbursement/store">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="reimbursementName" class="form-label">Reimbursement Name</label>
                    <input type="text" class="form-control" id="reimbursementName" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="reimbursementLimit" class="form-label">Reimbursement Limit</label>
                    <input type="text" class="form-control" id="reimbursementLimit" name="limit" placeholder="Enter limit or type 'UNLIMITED'" required>
                </div>

                <div class="mb-3">
                    <label for="expiredIn" class="form-label">Expired In</label>
                    <input type="text" class="form-control" id="expiredIn" name="expired_in" placeholder="E.g., yyyy-mm-dd or UNLIMITED" required>
                </div>

                <div class="mb-3">
                    <label for="effectiveDate" class="form-label">Effective Date</label>
                    <input type="date" class="form-control" id="effectiveDate" name="effective_date" required>
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

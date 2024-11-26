<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Edit Reimbursement Request</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/pegawai/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/pegawai/request">My Requests</a></li>
            <li class="breadcrumb-item active">Edit Request</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Request</h5>

            <form action="/pegawai/request/update/<?= $request['id'] ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="transaction_id" class="form-label">Transaction ID</label>
                    <input type="text" id="transaction_id" name="transaction_id" class="form-control" 
                           value="<?= esc($request['transaction_id']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="reimbursement_name" class="form-label">Reimbursement Name</label>
                    <select id="reimbursement_name" name="reimbursement_name" class="form-select" required>
                        <option value="">-- Select Reimbursement --</option>
                        <?php foreach ($assignedReimbursements as $reimbursement): ?>
                            <option value="<?= esc($reimbursement['reimbursement_id']) ?>" 
                                <?= $reimbursement['reimbursement_id'] == $request['reimbursement_name'] ? 'selected' : '' ?>>
                                <?= esc($reimbursement['reimbursement_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="effective_date" class="form-label">Effective Date</label>
                    <input type="date" id="effective_date" name="effective_date" class="form-control" 
                           value="<?= esc($request['effective_date']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required><?= esc($request['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Attachment File</label>
                    <?php if (!empty($request['attachment'])): ?>
                        <div class="mb-2">
                            <a href="<?= base_url('uploads/attachments/' . esc($request['attachment'])) ?>" target="_blank">
                                View Current Attachment
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="attachment" name="attachment" class="form-control">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/pegawai/request" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

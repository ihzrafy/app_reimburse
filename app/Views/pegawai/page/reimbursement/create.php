<?= $this->extend('pegawai/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Create Reimbursement Request</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/pegawai/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/pegawai/request">Reimbursement Requests</a></li>
            <li class="breadcrumb-item active">Create Request</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create New Reimbursement Request</h5>
            <form method="post" action="/pegawai/request/store" enctype="multipart/form-data">
                <!-- Transaction ID -->
                <div class="mb-3">
                    <label for="transactionId" class="form-label">Transaction ID</label>
                    <input type="text" id="transactionId" name="transaction_id" class="form-control" value="<?= esc($transaction_id) ?>" readonly>
                </div>

                <!-- Effective Date -->
                <div class="mb-3">
                    <label for="effectiveDate" class="form-label">Effective Date</label>
                    <input type="date" id="effectiveDate" name="effective_date" class="form-control" required>
                </div>

                <!-- Reimbursement Name -->
                <div class="mb-3">
                    <label for="reimbursementName" class="form-label">Reimbursement Name</label>
                    <select id="reimbursementName" name="reimbursement_name" class="form-select" required>
                        <option value="">-- Select Reimbursement Name --</option>
                        <?php foreach ($assignedReimbursements as $reimbursement): ?>
                            <option value="<?= esc($reimbursement['reimbursement_id']) ?>">
                                <?= esc($reimbursement['reimbursement_name']) ?> 
                                (Balance: Rp<?= number_format($reimbursement['balance'], 2, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>



                <!-- Pegawai -->
            
                <div class="mb-3">
                    <label for="pegawai" class="form-label">Pegawai</label>
                    <input type="text" id="pegawai" name="pegawai" class="form-control" 
                        value="<?= esc($currentPegawai['full_name'] . ' (' . $currentPegawai['pegawai_id'] . ')') ?>" readonly>
                    <input type="hidden" name="pegawai_id" value="<?= esc($currentPegawai['pegawai_id']) ?>">
                </div>


                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                </div>

                <!-- Attachment -->
                <div class="mb-3">
                    <label for="attachment" class="form-label">Attach File</label>
                    <input type="file" id="attachment" name="attachment" class="form-control">
                </div>

                <!-- Benefit Details -->
                <!-- Benefit Details -->
                <h5 class="mt-4">Benefit Details</h5>
                <table id="benefitsTable" class="table table-bordered">
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
                        <tr>
                            <td>1</td>
                            <td>
                                <select name="benefit_name[]" class="form-select" required>
                                    <option value="">-- Select Benefit --</option>
                                    <?php foreach ($assignedReimbursements as $reimbursement): ?>
                                        <option value="<?= esc($reimbursement['reimbursement_id']) ?>">
                                            <?= esc($reimbursement['reimbursement_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="request_amount[]" class="form-control" required></td>
                            <td><input type="number" name="paid_amount[]" class="form-control" required></td>
                            <td><input type="text" name="benefit_description[]" class="form-control"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-benefit"><i class="bi bi-x"></i></button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="addBenefitBtn" class="btn btn-outline-primary">Add Benefit</button>


                <!-- Submit -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="/pegawai/request" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        let benefitCount = 1;

        // Tambahkan benefit baru
        $('#addBenefitBtn').click(function () {
            benefitCount++;
            $('#benefitsTable tbody').append(`
                <tr>
                    <td>${benefitCount}</td>
                    <td>
                        <select name="benefit_name[]" class="form-select" required>
                            <option value="">-- Select Benefit --</option>
                            <option value="Biaya Transportasi">Biaya Transportasi</option>
                            <option value="Biaya Medis">Biaya Medis</option>
                        </select>
                    </td>
                    <td><input type="number" name="request_amount[]" class="form-control" required></td>
                    <td><input type="number" name="paid_amount[]" class="form-control" required></td>
                    <td><input type="text" name="benefit_description[]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-benefit">❌</button></td>
                </tr>
            `);
        });

        // Hapus benefit
        $(document).on('click', '.remove-benefit', function () {
            $(this).closest('tr').remove();
        });
    });
</script>

<?= $this->endSection() ?>

<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Edit Assignment</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/assignments">Assignments</a></li>
            <li class="breadcrumb-item active">Edit Assignment</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Assignment</h5>

            <!-- Form for editing assignment -->
            <form id="editForm" method="post" action="/admin/assignments/update/<?= $assignment['id'] ?>">
                <?= csrf_field() ?>
                <div class="row mb-3">
                    <label for="reimbursementName" class="col-sm-2 col-form-label">Reimbursement Name</label>
                    <div class="col-sm-10">
                        <select id="reimbursementName" name="reimbursement_name" class="form-select" required>
                            <?php foreach ($reimbursements as $reimbursement): ?>
                                <option value="<?= $reimbursement['id'] ?>" 
                                    <?= $reimbursement['id'] == $assignment['reimbursement_id'] ? 'selected' : '' ?>>
                                    <?= esc($reimbursement['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="type" class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <span class="d-block form-control">Assign</span>
                        <input type="hidden" name="type" value="Assign">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="employees" class="col-sm-2 col-form-label">Assign Employees</label>
                    <div class="col-sm-10">
                        <table id="employeeTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAllEmployees"></th>
                                    <th>No</th>
                                    <th>Employee ID</th>
                                    <th>Full Name</th>
                                    <th>Balance</th> <!-- Add Balance Column -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $index => $employee): ?>
                                    <?php 
                                    // Cek apakah pegawai sudah terhubung
                                    $isAssigned = isset($assignedEmployees[$employee['employee_id']]);
                                    $balance = $isAssigned ? $assignedEmployees[$employee['employee_id']]['balance'] : 0;
                                    ?>
                                    <tr>
                                        <!-- Checkbox -->
                                        <td>
                                            <input type="checkbox" name="employees[<?= esc($employee['employee_id']) ?>]" 
                                                value="<?= esc($employee['employee_id']) ?>" <?= $isAssigned ? 'checked' : '' ?>>
                                        </td>

                                        <!-- Data Pegawai -->
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($employee['employee_id']) ?></td>
                                        <td><?= esc($employee['full_name']) ?></td>

                                        <!-- Balance -->
                                        <td>
                                            <input type="number" name="balances[<?= esc($employee['employee_id']) ?>]" 
                                                class="form-control" value="<?= intval($balance) ?>" min="0" step="1">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>


                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea id="description" name="description" class="form-control" rows="3"><?= esc($assignment['description']) ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/assignments" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('#employeeTable').DataTable();

        // Select all employees
        $('#selectAllEmployees').on('click', function () {
            const isChecked = $(this).is(':checked');
            $('input[name="employees[]"]').each(function () {
                $(this).prop('checked', isChecked);
            });
        });

        // Update Select All checkbox state based on individual checkbox
        $('input[name="employees[]"]').on('change', function () {
            const totalCheckboxes = $('input[name="employees[]"]').length;
            const checkedCheckboxes = $('input[name="employees[]"]:checked').length;

            $('#selectAllEmployees').prop('checked', totalCheckboxes === checkedCheckboxes);
        });
    });
</script>
<?= $this->endSection() ?>

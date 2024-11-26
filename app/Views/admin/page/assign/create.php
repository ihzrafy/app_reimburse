    <?= $this->extend('admin/layout') ?>

    <?= $this->section('content') ?>

    <div class="pagetitle">
        <h1>Assign or Update Reimbursement</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                <li class="breadcrumb-item"><a href="/admin/reimbursement">Reimbursements</a></li>
                <li class="breadcrumb-item active">Assign or Update</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Assign or Update</h5>

                <!-- Form for selecting Reimbursement Name and Type -->
                <form id="assignForm" method="post" action="/admin/assignments/store">
                <div class="row mb-3">
                    <label for="reimbursementName" class="col-sm-2 col-form-label">Reimbursement Name</label>
                    <div class="col-sm-10">
                    <select id="reimbursementName" name="reimbursement_name" class="form-select" required>
                        <option value="">-- Choose --</option>
                        <?php foreach ($reimbursements as $reimbursement): ?>
                            <option value="<?= $reimbursement['id'] ?>">
                                <?= $reimbursement['name'] ?>
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
                                    <tr>
                                        <!-- Checkbox -->
                                        <td>
                                            <input type="checkbox" name="employees[<?= esc($employee['employee_id']) ?>]" value="<?= esc($employee['employee_id']) ?>">
                                        </td>

                                        <!-- Data Pegawai -->
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($employee['employee_id']) ?></td>
                                        <td><?= esc($employee['full_name']) ?></td>

                                        <!-- Balance -->
                                        <td>
                                            <input type="number" name="balances[<?= esc($employee['employee_id']) ?>]" class="form-control" placeholder="Enter balance" min="0" step="1">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>


                <!-- New Balance Section -->
                <!-- <div class="row mb-3">
                    <label for="balance" class="col-sm-2 col-form-label">Balance</label>
                    <div class="col-sm-10">
                        <input type="number" name="balance" id="balance" class="form-control" placeholder="Enter balance amount" min="0" step="0.01" required>
                    </div>
                </div> -->

                <div class="row mb-3">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>


                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>


                <div class="mt-4">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#employeeModal">Add Employees</button> -->
                    <!-- <button class="btn btn-outline-success me-2" onclick="window.location.href='/admin/reimbursement/import'">Import</button>
                    <button class="btn btn-outline-info me-2" onclick="window.location.href='/admin/reimbursement/export'">Export</button>
                    <button class="btn btn-outline-danger" onclick="window.location.href='/admin/reimbursement/delete'">Delete</button> -->
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <!--  -->
    </section>

    <!-- Modal -->
    <!--  -->


    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        let selectedEmployees = [];

        $(document).ready(function () {
            $('#employeeTable').DataTable();

            $('#selectAll').on('click', function () {
                $('input[name="employees[]"]').prop('checked', this.checked);
            });

            $('#saveSelection').on('click', function () {
                const checkedBoxes = $('input[name="employees[]"]:checked');
                let dataToSend = [];

                checkedBoxes.each(function () {
                    const employeeId = $(this).val();
                    const fullName = $(this).closest('tr').find('td:nth-child(4)').text();

                    if (!selectedEmployees.some(e => e.employee_id === employeeId)) {
                        selectedEmployees.push({ employee_id: employeeId, full_name: fullName });

                        dataToSend.push({ employee_id: employeeId });

                        const row = `
                            <tr data-employee-id="${employeeId}">
                                <td>${selectedEmployees.length}</td>
                                <td>${employeeId}</td>
                                <td>${fullName}</td>
                                <td><button class="btn btn-danger btn-sm remove-row">❌</button></td>
                            </tr>
                        `;
                        $('#employeeReimbursementBody').append(row);
                    }
                });

                fetch('/admin/reimbursement/save-selection', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({ employees: dataToSend })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Employees saved successfully!');
                    } else {
                        alert('Error saving employees: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));

                const modal = bootstrap.Modal.getInstance(document.getElementById('employeeModal'));
                modal.hide();
            });

            $('#employeeReimbursementBody').on('click', '.remove-row', function () {
                const employeeId = $(this).closest('tr').data('employee-id');
                selectedEmployees = selectedEmployees.filter(e => e.employee_id !== employeeId);
                $(this).closest('tr').remove();
            });
        });



    </script>
    <script>
        $(document).ready(function () {
            $('#selectAllEmployees').on('click', function () {
                $('input[name="employees[]"]').prop('checked', this.checked);
            });
        });
    </script>

    <?= $this->endSection() ?>

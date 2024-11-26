<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reimbursement Requests</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">All Reimbursement Requests</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Transaction ID</th>
                <th>Employee</th>
                <th>Reimbursement Name</th>
                <th>Effective Date</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $index => $request): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($request['transaction_id']) ?></td>
                    <td><?= esc($request['employee_name']) ?></td>
                    <td><?= esc($request['reimbursement_name']) ?></td>
                    <td><?= esc($request['effective_date']) ?></td>
                    <td><?= esc($request['description']) ?></td>
                    <td><?= esc($request['status']) ?></td>
                    <td><?= date('Y-m-d H:i:s', strtotime($request['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

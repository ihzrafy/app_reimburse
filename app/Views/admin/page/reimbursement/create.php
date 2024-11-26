<h2>Tambah Reimburse</h2>
<form action="/reimbursement/store" method="post">
    <label for="user_id">User ID:</label>
    <input type="text" name="user_id" id="user_id" required>
    <label for="amount">Amount:</label>
    <input type="text" name="amount" id="amount" required>
    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>
    <button type="submit">Submit</button>
</form>

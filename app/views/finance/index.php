<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Finance Management</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Add New Transaction / Payment</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/finance/create">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="transaction_date" class="form-label">Date</label>
                    <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type" class="form-label">Transaction Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="income">Income / Fee Payment</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select">
                        <option value="">None</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-control" maxlength="100" placeholder="e.g., Quarry fee paid" required>
                    <div id="description_feedback" class="invalid-feedback d-none">Valid characters only! Avoid special symbols.</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="payment_status" class="form-label">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Transaction</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th>Date</th><th>Type</th><th>Description</th><th>Customer</th><th>Status</th><th>Method</th><th>Amount</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($transaction['type'])) ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['customer_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars(ucfirst($transaction['payment_status'] ?? 'paid')) ?></td>
                    <td><?= htmlspecialchars(str_replace('_', ' ', ucfirst($transaction['payment_method'] ?? 'cash'))) ?></td>
                    <td><?= number_format($transaction['amount'], 2) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/finance/edit?id=<?= $transaction['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if (($_SESSION['role'] ?? '') === 'administrator'): ?>
                            <a href="<?= APP_URL ?>/finance/delete?id=<?= $transaction['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this transaction?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const descriptionInput = document.getElementById('description');
    const descriptionFeedback = document.getElementById('description_feedback');

    function setDescriptionInvalid(message) {
        descriptionInput.classList.add('is-invalid');
        descriptionInput.classList.remove('is-valid');
        descriptionFeedback.textContent = message;
        descriptionFeedback.classList.remove('d-none');
    }

    function clearDescriptionValidation() {
        descriptionInput.classList.remove('is-invalid');
        descriptionInput.classList.add('is-valid');
        descriptionFeedback.classList.add('d-none');
    }

    descriptionInput.addEventListener('input', () => {
        const filtered = descriptionInput.value.replace(/[^A-Za-z0-9\s\.,#]/g, '');
        if (descriptionInput.value !== filtered) {
            descriptionInput.value = filtered;
            setDescriptionInvalid('Valid characters only! Avoid special symbols.');
        } else {
            clearDescriptionValidation();
        }
    });

    descriptionInput.addEventListener('blur', () => {
        let value = descriptionInput.value.replace(/\s+/g, ' ').trim();
        descriptionInput.value = value;
        if (value.length < 5) {
            setDescriptionInvalid('Description is too brief.');
        } else {
            clearDescriptionValidation();
        }
    });
</script>

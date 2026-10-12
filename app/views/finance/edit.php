<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Transaction</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/finance/update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($transaction['id']) ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="transaction_date" class="form-label">Date</label>
                    <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="<?= htmlspecialchars($transaction['transaction_date']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type" class="form-label">Transaction Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="income" <?= $transaction['type'] === 'income' ? 'selected' : '' ?>>Income / Fee Payment</option>
                        <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Expense</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" value="<?= htmlspecialchars($transaction['amount']) ?>" step="0.01" min="0" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select">
                        <option value="">None</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>" <?= ($transaction['customer_id'] ?? '') == $customer['id'] ? 'selected' : '' ?>><?= htmlspecialchars($customer['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="<?= htmlspecialchars($transaction['description']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="payment_status" class="form-label">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="form-select" required>
                        <option value="paid" <?= ($transaction['payment_status'] ?? 'paid') === 'paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="pending" <?= ($transaction['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="overdue" <?= ($transaction['payment_status'] ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="cash" <?= ($transaction['payment_method'] ?? 'cash') === 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="bank_transfer" <?= ($transaction['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                        <option value="mobile_money" <?= ($transaction['payment_method'] ?? '') === 'mobile_money' ? 'selected' : '' ?>>Mobile Money</option>
                        <option value="other" <?= ($transaction['payment_method'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= APP_URL ?>/finance" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

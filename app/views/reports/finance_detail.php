<div class="mb-4">
    <a href="<?= APP_URL ?>/reports" class="btn btn-sm btn-outline-secondary">← Back to Reports</a>
    <h2 class="fw-bold mt-3">Finance Report - Financial Analytics</h2>
    <p class="text-muted">Revenue, expenses, payment status and outstanding balances with detailed breakdowns.</p>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-success">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Total Revenue</p>
                <h3 class="fw-bold text-success mb-0"><?= number_format($summary['total_revenue'] ?? 0, 0) ?></h3>
                <p class="text-muted small">RWF</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-danger">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Total Expenses</p>
                <h3 class="fw-bold text-danger mb-0"><?= number_format($summary['total_expenses'] ?? 0, 0) ?></h3>
                <p class="text-muted small">RWF</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-info">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Paid Income</p>
                <h3 class="fw-bold text-info mb-0"><?= number_format($summary['total_paid_income'] ?? 0, 0) ?></h3>
                <p class="text-muted small">RWF</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-warning">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Pending / Overdue</p>
                <h3 class="fw-bold text-warning mb-0"><?= number_format(($summary['total_pending_income'] ?? 0) + ($summary['total_overdue_income'] ?? 0), 0) ?></h3>
                <p class="text-muted small">RWF</p>
            </div>
        </div>
    </div>
</div>

<!-- Financial Transactions Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Financial Transactions</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Amount (RWF)</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($transactions)): 
                    $balance = 0;
                    foreach ($transactions as $transaction):
                        if ($transaction['type'] === 'income') {
                            $balance += $transaction['amount'];
                        } else {
                            $balance -= $transaction['amount'];
                        }
                ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                        <td>
                            <?php if ($transaction['type'] === 'income'): ?>
                                <span class="badge bg-success">Income</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Expense</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($transaction['description']) ?></td>
                        <td><?= htmlspecialchars($transaction['customer_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars(ucfirst($transaction['payment_status'] ?? 'paid')) ?></td>
                        <td><?= htmlspecialchars(str_replace('_', ' ', ucfirst($transaction['payment_method'] ?? 'cash'))) ?></td>
                        <td>
                            <?php if ($transaction['type'] === 'income'): ?>
                                <span class="text-success fw-bold">+<?= number_format($transaction['amount'], 0) ?></span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">-<?= number_format($transaction['amount'], 0) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($balance, 0) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No transactions found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Export Section -->
<div class="mt-4 text-end">
    <button class="btn btn-sm btn-outline-secondary print-page">Print Report</button>
    <button class="btn btn-sm btn-outline-primary">Download CSV</button>
</div>

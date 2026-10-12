<div class="mb-4">
    <a href="<?= APP_URL ?>/reports" class="btn btn-sm btn-outline-secondary">← Back to Reports</a>
    <h2 class="fw-bold mt-3">Production Report - Detailed Analytics</h2>
    <p class="text-muted">Daily, weekly, monthly and yearly production summaries with detailed breakdowns.</p>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Total Produced</p>
                <h3 class="fw-bold text-primary mb-0">14,250</h3>
                <p class="text-muted small">tonnes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">This Period</p>
                <h3 class="fw-bold text-success mb-0">351</h3>
                <p class="text-muted small">tonnes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Average/Day</p>
                <h3 class="fw-bold text-info mb-0">50.1</h3>
                <p class="text-muted small">tonnes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Target This Month</p>
                <h3 class="fw-bold text-warning mb-0">1,500</h3>
                <p class="text-muted small">tonnes</p>
            </div>
        </div>
    </div>
</div>

<!-- Production Records Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Production Records</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Quantity (tonnes)</th>
                    <th>Note</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productions)): ?>
                    <?php foreach ($productions as $production): ?>
                        <tr>
                            <td><?= htmlspecialchars($production['recorded_on']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($production['quantity_tonnes']) ?></span></td>
                            <td><?= htmlspecialchars($production['note'] ?? 'N/A') ?></td>
                            <td><span class="badge bg-success">Recorded</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No production records found</td>
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

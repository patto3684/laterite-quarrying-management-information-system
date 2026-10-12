<div class="mb-4">
    <a href="<?= APP_URL ?>/reports" class="btn btn-sm btn-outline-secondary">← Back to Reports</a>
    <h2 class="fw-bold mt-3">Safety & Environmental Report - Compliance Audit</h2>
    <p class="text-muted">Summarize site inspection passes, environmental rehabilitation fund allocations, and risk factor audits.</p>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-success">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Inspections Passed</p>
                <h3 class="fw-bold text-success mb-0">12</h3>
                <p class="text-muted small">sites</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-danger">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Issues Found</p>
                <h3 class="fw-bold text-danger mb-0">1</h3>
                <p class="text-muted small">pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-info">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Rehab Fund Allocated</p>
                <h3 class="fw-bold text-info mb-0">2.4M</h3>
                <p class="text-muted small">RWF</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-warning">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Compliance Rate</p>
                <h3 class="fw-bold text-warning mb-0">92%</h3>
                <p class="text-muted small">Excellent</p>
            </div>
        </div>
    </div>
</div>

<!-- Inspection Records -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Inspections</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Site</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inspections)): ?>
                            <?php foreach (array_slice($inspections, 0, 5) as $inspection): ?>
                                <tr>
                                    <td><?= htmlspecialchars($inspection['title']) ?></td>
                                    <td>
                                        <?php if ($inspection['status'] === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($inspection['scheduled_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No inspections found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Environmental Compliance</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Requirement</th>
                            <th>Status</th>
                            <th>Fund Allocated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dust Control</td>
                            <td><span class="badge bg-success">Compliant</span></td>
                            <td>600,000 RWF</td>
                        </tr>
                        <tr>
                            <td>Water Management</td>
                            <td><span class="badge bg-success">Compliant</span></td>
                            <td>800,000 RWF</td>
                        </tr>
                        <tr>
                            <td>Land Rehabilitation</td>
                            <td><span class="badge bg-warning">In Progress</span></td>
                            <td>1,000,000 RWF</td>
                        </tr>
                        <tr>
                            <td>Waste Management</td>
                            <td><span class="badge bg-success">Compliant</span></td>
                            <td>400,000 RWF</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Risk Assessment -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Risk Factor Audit</h5>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <thead class="table-light">
                <tr>
                    <th>Risk Factor</th>
                    <th>Level</th>
                    <th>Mitigation Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Equipment Failures</td>
                    <td><span class="badge bg-success">Low</span></td>
                    <td>Regular maintenance schedule in place</td>
                </tr>
                <tr>
                    <td>Worker Injuries</td>
                    <td><span class="badge bg-success">Low</span></td>
                    <td>Safety training completed for all staff</td>
                </tr>
                <tr>
                    <td>Environmental Damage</td>
                    <td><span class="badge bg-warning">Medium</span></td>
                    <td>Rehabilitation fund active, land restoration ongoing</td>
                </tr>
                <tr>
                    <td>Regulatory Non-Compliance</td>
                    <td><span class="badge bg-success">Low</span></td>
                    <td>Quarterly audits conducted</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Export Section -->
<div class="mt-4 text-end">
    <button class="btn btn-sm btn-outline-secondary print-page">Print Report</button>
    <button class="btn btn-sm btn-outline-primary">Download CSV</button>
</div>

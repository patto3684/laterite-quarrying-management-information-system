<div class="mb-4">
    <a href="<?= APP_URL ?>/reports" class="btn btn-sm btn-outline-secondary">← Back to Reports</a>
    <h2 class="fw-bold mt-3">Logistics & Dispatch Report - Fleet Operations</h2>
    <p class="text-muted">Track haulage truck fleet metrics, total dispatched trips, and route distributions within Kigali.</p>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Total Fleet Size</p>
                <h3 class="fw-bold text-primary mb-0">12</h3>
                <p class="text-muted small">trucks</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Active Vehicles</p>
                <h3 class="fw-bold text-success mb-0">9</h3>
                <p class="text-muted small">78% utilization</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Total Trips Today</p>
                <h3 class="fw-bold text-warning mb-0">47</h3>
                <p class="text-muted small">dispatched</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="text-muted small mb-1">Total Tonnage Moved</p>
                <h3 class="fw-bold text-info mb-0">2,380</h3>
                <p class="text-muted small">tonnes</p>
            </div>
        </div>
    </div>
</div>

<!-- Fleet Status -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Vehicle Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Vehicle ID</th>
                            <th>Status</th>
                            <th>Last Trip</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TRK-001</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>Kigali → Jabana</td>
                        </tr>
                        <tr>
                            <td>TRK-002</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>Kigali → Gatsata</td>
                        </tr>
                        <tr>
                            <td>TRK-003</td>
                            <td><span class="badge bg-warning">Maintenance</span></td>
                            <td>Routine Check</td>
                        </tr>
                        <tr>
                            <td>TRK-004</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>Kigali → Ndera</td>
                        </tr>
                        <tr>
                            <td>TRK-005</td>
                            <td><span class="badge bg-danger">Out of Service</span></td>
                            <td>Major Repair</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Route Distribution</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Route</th>
                            <th>Trips</th>
                            <th>Tonnage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kigali → Jabana Site</td>
                            <td><span class="badge bg-primary">18</span></td>
                            <td>900 tonnes</td>
                        </tr>
                        <tr>
                            <td>Kigali → Gatsata Site</td>
                            <td><span class="badge bg-primary">15</span></td>
                            <td>750 tonnes</td>
                        </tr>
                        <tr>
                            <td>Kigali → Ndera Site</td>
                            <td><span class="badge bg-primary">12</span></td>
                            <td>600 tonnes</td>
                        </tr>
                        <tr>
                            <td>Inter-site Transfers</td>
                            <td><span class="badge bg-primary">2</span></td>
                            <td>130 tonnes</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="mt-4 text-end">
    <button class="btn btn-sm btn-outline-secondary print-page">Print Report</button>
    <button class="btn btn-sm btn-outline-primary">Download CSV</button>
</div>

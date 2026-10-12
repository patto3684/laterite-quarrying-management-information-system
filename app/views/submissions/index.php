<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Daily Submissions Overview</h2>
        <p class="text-muted">Leader view for all submitted operational records today.</p>
    </div>
    <button class="btn btn-sm btn-outline-secondary print-page">Print</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-uppercase text-muted mb-2">Production</h6>
            <h3><?= $summary['production'] ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-uppercase text-muted mb-2">Equipment</h6>
            <h3><?= $summary['equipment'] ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-uppercase text-muted mb-2">Inspections</h6>
            <h3><?= $summary['inspection'] ?></h3>
        </div>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-uppercase text-muted mb-2">Finance</h6>
            <h3><?= $summary['finance'] ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-uppercase text-muted mb-2">Customers</h6>
            <h3><?= $summary['customer'] ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6 class="text-uppercase text-muted mb-2">Other</h6>
            <h3><?= $summary['other'] ?></h3>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Recent Submissions</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?= htmlspecialchars($submission['created_at']) ?></td>
                        <td><?= htmlspecialchars($submission['title']) ?></td>
                        <td><?= htmlspecialchars($submission['detail']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

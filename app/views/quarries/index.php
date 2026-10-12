<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Quarry Sites</h2>
    <a href="<?= APP_URL ?>/quarries/create" class="btn btn-primary">Register Quarry</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>District</th><th>Sector</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($quarries as $quarry): ?>
                <tr><td><?= htmlspecialchars($quarry['name']) ?></td><td><?= htmlspecialchars($quarry['district']) ?></td><td><?= htmlspecialchars($quarry['sector']) ?></td><td><?= htmlspecialchars($quarry['status']) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

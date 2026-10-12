<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><?= (strtolower($role ?? '') === 'administrator') ? 'Admin Dashboard' : 'Dashboard' ?></h2>
        <p class="text-muted"><?= (strtolower($role ?? '') === 'administrator') ? 'System Console & Integrity Overview' : 'Laterite Quarry Management Information System' ?></p>
    </div>
    <span class="badge bg-primary fs-6">Role: <?= htmlspecialchars($role) ?></span>
</div>

<?php if (strtolower($role ?? '') === 'administrator'): ?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Total Registered Users</h6>
                <h3><?= number_format($adminStats['total_users_active'] ?? 0) ?> <small class="text-muted">(active)</small></h3>
                <?php if (!empty($adminStats['total_users_inactive'])): ?>
                    <small class="text-muted"><?= number_format($adminStats['total_users_inactive']) ?> inactive</small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">System Health Status</h6>
                <h3><?= (!empty($adminStats['db_ok']) ? 'OK' : 'DB ERROR') ?></h3>
                <small class="text-muted">Uptime: <?= isset($adminStats['uptime_seconds']) ? gmdate('H:i:s', (int)$adminStats['uptime_seconds']) : 'n/a' ?></small><br>
                <small class="text-muted">Last Backup: <?= htmlspecialchars($adminStats['last_backup'] ?? 'Never') ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Backup Volume Usage</h6>
                <h3><?= isset($adminStats['backup_volume_bytes']) ? number_format($adminStats['backup_volume_bytes'] / 1024 / 1024, 2) . ' MB' : '0 MB' ?></h3>
                <small class="text-muted">Backups folder size</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Daily System Activity</h6>
                <h3><?= number_format($adminStats['daily_activity_count'] ?? 0) ?></h3>
                <small class="text-muted">Operations logged today</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Recent System Activities</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach (($recentActivities ?? []) as $activity): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($activity['action'] ?? ($activity['title'] ?? '')) ?></strong>
                            <div><small class="text-muted"><?= htmlspecialchars($activity['user'] ?? ($activity['detail'] ?? '')) ?> - <?= htmlspecialchars($activity['created_at'] ?? '') ?></small></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Quick Metrics</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Active Sessions: <?= number_format($quickMetrics['active_sessions'] ?? 0) ?></li>
                    <li class="list-group-item">Failed Login Attempts (week): <?= number_format($quickMetrics['failed_logins'] ?? 0) ?></li>
                    <li class="list-group-item">Pending Account Activations: <?= number_format($quickMetrics['pending_activations'] ?? 0) ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Total Quarry Sites</h6>
                <h3><?= $stats['quarries'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Laterite Produced</h6>
                <h3><?= $stats['production'] ?> t</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Available Stock</h6>
                <h3><?= $stats['stock'] ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm card-stat">
            <div class="card-body">
                <h6 class="text-muted">Total Revenue</h6>
                <h3><?= number_format($stats['revenue']) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Recent Activities</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($activities as $activity): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($activity['title']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($activity['detail']) ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Quick Metrics</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Pending Inspections: <?= $stats['pending_inspections'] ?></li>
                    <li class="list-group-item">Completed Inspections: <?= $stats['completed_inspections'] ?></li>
                    <li class="list-group-item">Expenses: <?= number_format($stats['expenses']) ?></li>
                    <li class="list-group-item">Active Users: <?= $stats['users'] ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

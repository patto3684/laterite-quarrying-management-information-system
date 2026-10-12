<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Audit Log</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <p class="text-muted">Recent system activities and audit records.</p>
        <ul class="list-group mt-3">
            <?php foreach (($data['activities'] ?? []) as $act): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong><?php echo htmlspecialchars($act['title']); ?></strong>
                            <div class="small text-muted"><?php echo htmlspecialchars($act['detail']); ?></div>
                        </div>
                        <div class="small text-muted"><?php echo $act['created_at']; ?></div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

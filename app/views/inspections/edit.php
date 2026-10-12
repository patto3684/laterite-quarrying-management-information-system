<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Inspection</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/inspections/update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($inspection['id']) ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($inspection['title']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="quarry_id" class="form-label">Quarry</label>
                    <select name="quarry_id" id="quarry_id" class="form-select" required>
                        <option value="">-- Select Quarry --</option>
                        <?php foreach ($quarries as $quarry): ?>
                            <option value="<?= htmlspecialchars($quarry['id']) ?>" <?= $inspection['quarry_id'] == $quarry['id'] ? 'selected' : '' ?>><?= htmlspecialchars($quarry['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="scheduled_date" class="form-label">Scheduled Date</label>
                    <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" value="<?= htmlspecialchars($inspection['scheduled_date']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending" <?= $inspection['status'] === 'pending' ? 'selected' : '' ?>>pending</option>
                        <option value="completed" <?= $inspection['status'] === 'completed' ? 'selected' : '' ?>>completed</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= APP_URL ?>/inspections" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

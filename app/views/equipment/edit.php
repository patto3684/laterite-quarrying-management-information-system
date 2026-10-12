<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Equipment</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/equipment/update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($equipment['id']) ?>">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Equipment Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($equipment['name']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="available" <?= $equipment['status'] === 'available' ? 'selected' : '' ?>>available</option>
                        <option value="maintenance" <?= $equipment['status'] === 'maintenance' ? 'selected' : '' ?>>maintenance</option>
                        <option value="out_of_service" <?= $equipment['status'] === 'out_of_service' ? 'selected' : '' ?>>out_of_service</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="last_maintenance" class="form-label">Last Maintenance</label>
                    <input type="date" name="last_maintenance" id="last_maintenance" class="form-control" value="<?= htmlspecialchars($equipment['last_maintenance']) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= APP_URL ?>/equipment" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

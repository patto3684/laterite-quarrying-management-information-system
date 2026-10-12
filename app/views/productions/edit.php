<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Production Record</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/productions/update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($production['id']) ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="recorded_on" class="form-label">Date</label>
                    <input type="date" name="recorded_on" id="recorded_on" class="form-control" value="<?= htmlspecialchars($production['recorded_on']) ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="quarry_id" class="form-label">Quarry</label>
                    <select name="quarry_id" id="quarry_id" class="form-select" required>
                        <option value="">-- Select Quarry --</option>
                        <?php foreach ($quarries as $quarry): ?>
                            <option value="<?= htmlspecialchars($quarry['id']) ?>" <?= $production['quarry_id'] == $quarry['id'] ? 'selected' : '' ?>><?= htmlspecialchars($quarry['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="quantity_tonnes" class="form-label">Quantity (tonnes)</label>
                    <input type="number" name="quantity_tonnes" id="quantity_tonnes" class="form-control" value="<?= htmlspecialchars($production['quantity_tonnes']) ?>" step="0.01" min="0" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="note" class="form-label">Supplementary Note</label>
                    <input type="text" name="note" id="note" class="form-control" value="<?= htmlspecialchars($production['note']) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= APP_URL ?>/productions" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

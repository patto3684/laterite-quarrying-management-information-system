<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="mb-3">Edit Stock Item</h3>
        <form method="post" action="<?= APP_URL ?>/stocks/update">
            <input type="hidden" name="id" value="<?= $stock['id'] ?>">
            <div class="mb-3"><label>Item Name</label><input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($stock['item_name']) ?>" required></div>
            <div class="mb-3"><label>Quantity Available</label><input type="number" name="quantity_available" class="form-control" value="<?= htmlspecialchars($stock['quantity_available']) ?>" min="0" required></div>
            <div class="mb-3"><label>Unit</label><select name="unit" class="form-select"><option value="tons">Tons</option><option value="liters">Liters</option><option value="units">Units</option><option value="kg">Kg</option></select></div>
            <button class="btn btn-primary">Update</button>
            <a href="<?= APP_URL ?>/stocks" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script>
    document.querySelector('select[name="unit"]').value = <?= json_encode($stock['unit']) ?>;
</script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Stock Management</h2>
    <a href="<?= APP_URL ?>/stocks/create" class="btn btn-primary">Add Stock Item</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th>Item</th><th>Quantity</th><th>Unit</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($stocks as $stock): ?>
                <tr>
                    <td><?= htmlspecialchars($stock['item_name']) ?></td>
                    <td><?= htmlspecialchars($stock['quantity_available']) ?></td>
                    <td><?= htmlspecialchars($stock['unit']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/stocks/edit?id=<?= $stock['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <button class="btn btn-sm btn-outline-danger btn-delete-stock" data-stock-id="<?= $stock['id'] ?>" data-stock-name="<?= htmlspecialchars($stock['item_name']) ?>" data-delete-url="<?= APP_URL ?>/stocks/delete?id=<?= $stock['id'] ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<form method="post" id="deleteStockForm" style="display:none;">
    <input type="hidden" name="delete_id" id="deleteStockId">
</form>

<div id="deleteStockModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.3); max-width:500px; width:90%; overflow:hidden;">
        <div style="padding:15px 20px; border-bottom:1px solid #dee2e6; display:flex; justify-content:space-between; align-items:center;">
            <h5 style="margin:0; font-size:18px; font-weight:600;">Delete Stock Item</h5>
            <button type="button" id="closeDeleteStockModal" style="background:none; border:none; font-size:24px; cursor:pointer; color:#aaa;">&times;</button>
        </div>
        <div style="padding:20px;">
            <p>Are you sure you want to delete <strong id="deleteStockName"></strong>?</p>
        </div>
        <div style="padding:15px 20px; border-top:1px solid #dee2e6; display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-secondary" id="cancelDeleteStockModal">Cancel</button>
            <button type="submit" class="btn btn-danger" form="deleteStockForm">Delete</button>
        </div>
    </div>
</div>

<script>
    (function() {
        var deleteButtons = document.querySelectorAll('.btn-delete-stock');
        var deleteStockModal = document.getElementById('deleteStockModal');
        var deleteStockForm = document.getElementById('deleteStockForm');
        var deleteStockName = document.getElementById('deleteStockName');
        var closeDeleteStockModal = document.getElementById('closeDeleteStockModal');
        var cancelDeleteStockModal = document.getElementById('cancelDeleteStockModal');

        function showModal(stockName, deleteId) {
            deleteStockName.textContent = stockName;
            deleteStockForm.setAttribute('action', '<?= APP_URL ?>/stocks/delete');
            document.getElementById('deleteStockId').value = deleteId;
            deleteStockModal.style.display = 'flex';
        }

        function hideModal() {
            deleteStockModal.style.display = 'none';
        }

        for (var i = 0; i < deleteButtons.length; i++) {
            (function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var stockName = btn.getAttribute('data-stock-name');
                    var deleteId = btn.getAttribute('data-stock-id');
                    showModal(stockName, deleteId);
                });
            })(deleteButtons[i]);
        }

        closeDeleteStockModal.addEventListener('click', hideModal);
        cancelDeleteStockModal.addEventListener('click', hideModal);
        deleteStockModal.addEventListener('click', function(e) {
            if (e.target === deleteStockModal) {
                hideModal();
            }
        });
    })();
</script>

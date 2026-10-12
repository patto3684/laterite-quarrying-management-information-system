<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">User Management</h2>
    <a href="<?= APP_URL ?>/users/create" class="btn btn-primary">Add User</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/users/edit?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <button class="btn btn-sm btn-outline-danger btn-delete-user" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['full_name']) ?>" data-delete-url="<?= APP_URL ?>/users/delete?id=<?= $user['id'] ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<form method="post" id="deleteUserForm" style="display:none;">
    <input type="hidden" name="delete_id" id="deleteUserId">
</form>

<div id="deleteUserModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.3); max-width:500px; width:90%; overflow:hidden;">
        <div style="padding:15px 20px; border-bottom:1px solid #dee2e6; display:flex; justify-content:space-between; align-items:center;">
            <h5 style="margin:0; font-size:18px; font-weight:600;">Delete User</h5>
            <button type="button" id="closeDeleteModal" style="background:none; border:none; font-size:24px; cursor:pointer; color:#aaa;">&times;</button>
        </div>
        <div style="padding:20px;">
            <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
        </div>
        <div style="padding:15px 20px; border-top:1px solid #dee2e6; display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-secondary" id="cancelDeleteModal">Cancel</button>
            <button type="submit" class="btn btn-danger" form="deleteUserForm">Delete</button>
        </div>
    </div>
</div>

<script>
    (function() {
        var deleteButtons = document.querySelectorAll('.btn-delete-user');
        var deleteUserModal = document.getElementById('deleteUserModal');
        var deleteUserForm = document.getElementById('deleteUserForm');
        var deleteUserName = document.getElementById('deleteUserName');
        var deleteUserId = document.getElementById('deleteUserId');
        var closeDeleteModal = document.getElementById('closeDeleteModal');
        var cancelDeleteModal = document.getElementById('cancelDeleteModal');

        function showModal(userName, deleteUrl) {
            deleteUserName.textContent = userName;
            deleteUserForm.setAttribute('action', deleteUrl);
            deleteUserModal.style.display = 'flex';
        }

        function hideModal() {
            deleteUserModal.style.display = 'none';
        }

        for (var i = 0; i < deleteButtons.length; i++) {
            (function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var userName = btn.getAttribute('data-user-name');
                    var deleteUrl = btn.getAttribute('data-delete-url');
                    showModal(userName, deleteUrl);
                });
            })(deleteButtons[i]);
        }

        closeDeleteModal.addEventListener('click', hideModal);
        cancelDeleteModal.addEventListener('click', hideModal);
        deleteUserModal.addEventListener('click', function(e) {
            if (e.target === deleteUserModal) {
                hideModal();
            }
        });
    })();
</script>

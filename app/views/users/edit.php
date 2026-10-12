<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="mb-3">Edit User</h3>
        <form method="post" action="<?= APP_URL ?>/users/update">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required></div>
            <div class="mb-3"><label>Role</label><select name="role" class="form-select"><option value="administrator">Administrator</option><option value="quarry_officer">Quarry Officer</option><option value="inspector_officer">Inspector Officer</option><option value="finance_officer">Finance Officer</option><option value="leader">Leader</option></select></div>
            <button class="btn btn-primary">Update</button>
            <a href="<?= APP_URL ?>/users" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script>
    document.querySelector('select[name="role"]').value = <?= json_encode($user['role']) ?>;
</script>

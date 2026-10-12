<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Users</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <p class="text-muted">Reset passwords for users or review account details below.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($data['users'] ?? []) as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td>
                        <form method="post" action="/lqsmis/settings/reset_password" class="d-flex">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <input type="password" name="new_password" placeholder="New password" class="form-control form-control-sm me-2" required>
                            <button class="btn btn-sm btn-primary">Reset</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

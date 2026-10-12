<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Customer</h2>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/customers/update">
            <input type="hidden" name="id" value="<?= htmlspecialchars($customer['id']) ?>">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Customer Name</label>
                    <input type="text" name="name" id="name" class="form-control <?= !empty($_SESSION['form_errors']['name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_SESSION['form_old']['name'] ?? $customer['name']) ?>" required>
                    <?php if (!empty($_SESSION['form_errors']['name'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['name']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control <?= !empty($_SESSION['form_errors']['contact_person']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_SESSION['form_old']['contact_person'] ?? $customer['contact_person']) ?>" required>
                    <?php if (!empty($_SESSION['form_errors']['contact_person'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['contact_person']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control <?= !empty($_SESSION['form_errors']['phone']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_SESSION['form_old']['phone'] ?? $customer['phone']) ?>" required>
                    <?php if (!empty($_SESSION['form_errors']['phone'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['phone']) ?></div><?php endif; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= APP_URL ?>/customers" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php if (!empty($_SESSION['form_errors'])): ?>
    <?php unset($_SESSION['form_errors'], $_SESSION['form_old']); ?>
<?php endif; ?>

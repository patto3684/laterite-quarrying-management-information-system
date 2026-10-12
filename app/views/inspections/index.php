<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Inspection Management</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Add New Inspection</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/inspections/create">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" maxlength="150" placeholder="e.g., Safety compliance review" required>
                    <div id="title_feedback" class="invalid-feedback d-none">Valid characters only! Avoid special symbols.</div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="quarry_id" class="form-label">Quarry</label>
                    <select name="quarry_id" id="quarry_id" class="form-select" required>
                        <option value="">-- Select Quarry --</option>
                        <?php foreach ($quarries as $quarry): ?>
                            <option value="<?= htmlspecialchars($quarry['id']) ?>"><?= htmlspecialchars($quarry['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="scheduled_date" class="form-label">Scheduled Date</label>
                    <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Inspection</button>
        </form>
    </div>
</div>

<script>
    const titleInput = document.getElementById('title');
    const titleFeedback = document.getElementById('title_feedback');
    const inspectionForm = titleInput.closest('form');

    function setTitleInvalid(message) {
        titleInput.classList.add('is-invalid');
        titleInput.classList.remove('is-valid');
        titleFeedback.textContent = message;
        titleFeedback.classList.remove('d-none');
    }

    function clearTitleValidation() {
        titleInput.classList.remove('is-invalid');
        titleInput.classList.add('is-valid');
        titleFeedback.classList.add('d-none');
    }

    function cleanTitleValue(value) {
        return value.replace(/[^A-Za-z\s\.-]/g, '').replace(/\s+/g, ' ').trim();
    }

    titleInput.addEventListener('input', () => {
        const filtered = titleInput.value.replace(/[^A-Za-z\s\.-]/g, '');
        if (titleInput.value !== filtered) {
            titleInput.value = filtered;
            setTitleInvalid('Valid characters only! Avoid special symbols.');
        } else {
            clearTitleValidation();
        }
    });

    titleInput.addEventListener('blur', () => {
        const cleaned = cleanTitleValue(titleInput.value);
        titleInput.value = cleaned;
        if (cleaned.length < 4) {
            setTitleInvalid('Title is too brief.');
        } else {
            clearTitleValidation();
        }
    });

    inspectionForm?.addEventListener('submit', (event) => {
        const value = cleanTitleValue(titleInput.value);
        titleInput.value = value;
        if (value.length < 4) {
            event.preventDefault();
            setTitleInvalid('Title is too brief.');
            titleInput.focus();
        }
    });
</script>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Quarry</th>
                    <th>Scheduled Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($inspections as $inspection): ?>
                <tr>
                    <td><?= htmlspecialchars($inspection['title']) ?></td>
                    <td><?= htmlspecialchars($inspection['quarry_name']) ?></td>
                    <td><?= htmlspecialchars($inspection['scheduled_date']) ?></td>
                    <td><?= htmlspecialchars($inspection['status']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/inspections/edit?id=<?= $inspection['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if (($_SESSION['role'] ?? '') === 'administrator'): ?>
                            <a href="<?= APP_URL ?>/inspections/delete?id=<?= $inspection['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this inspection?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

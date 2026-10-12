<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Equipment Management</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Add New Equipment</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/equipment/create">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Equipment Name</label>
                    <input type="text" name="name" id="name" class="form-control" maxlength="150" placeholder="e.g., Loader Arm" required>
                    <div id="name_feedback" class="invalid-feedback d-none">Valid characters only! Avoid special symbols.</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="available">available</option>
                        <option value="maintenance">maintenance</option>
                        <option value="out_of_service">out_of_service</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="last_maintenance" class="form-label">Last Maintenance</label>
                    <input type="date" name="last_maintenance" id="last_maintenance" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Equipment</button>
        </form>
    </div>
</div>

<script>
    const equipmentNameInput = document.getElementById('name');
    const equipmentNameFeedback = document.getElementById('name_feedback');
    const equipmentForm = equipmentNameInput.closest('form');

    function setNameInvalid(message) {
        equipmentNameInput.classList.add('is-invalid');
        equipmentNameInput.classList.remove('is-valid');
        equipmentNameFeedback.textContent = message;
        equipmentNameFeedback.classList.remove('d-none');
    }

    function clearNameValidation() {
        equipmentNameInput.classList.remove('is-invalid');
        equipmentNameInput.classList.add('is-valid');
        equipmentNameFeedback.classList.add('d-none');
    }

    function cleanEquipmentName(value) {
        return value.replace(/[^A-Za-z\s\.-]/g, '').replace(/\s+/g, ' ').trim();
    }

    equipmentNameInput.addEventListener('input', () => {
        const filtered = equipmentNameInput.value.replace(/[^A-Za-z\s\.-]/g, '');
        if (equipmentNameInput.value !== filtered) {
            equipmentNameInput.value = filtered;
            setNameInvalid('Valid characters only! Avoid special symbols.');
        } else {
            clearNameValidation();
        }
    });

    equipmentNameInput.addEventListener('blur', () => {
        const cleaned = cleanEquipmentName(equipmentNameInput.value);
        equipmentNameInput.value = cleaned;
        if (cleaned.length < 4) {
            setNameInvalid('Name is too brief.');
        } else {
            clearNameValidation();
        }
    });

    equipmentForm?.addEventListener('submit', (event) => {
        const value = cleanEquipmentName(equipmentNameInput.value);
        equipmentNameInput.value = value;
        if (value.length < 4) {
            event.preventDefault();
            setNameInvalid('Name is too brief.');
            equipmentNameInput.focus();
        }
    });
</script>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Last Maintenance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($equipment as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['status']) ?></td>
                    <td><?= htmlspecialchars($item['last_maintenance']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/equipment/edit?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if (($_SESSION['role'] ?? '') === 'administrator'): ?>
                            <a href="<?= APP_URL ?>/equipment/delete?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this equipment item?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

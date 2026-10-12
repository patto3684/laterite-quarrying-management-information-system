<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Production Management</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Add New Production Record</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/productions/create">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="recorded_on" class="form-label">Date</label>
                    <input type="date" name="recorded_on" id="recorded_on" class="form-control" value="<?= date('Y-m-d') ?>" required>
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
                    <label for="quantity_tonnes" class="form-label">Quantity (tonnes)</label>
                    <input type="number" name="quantity_tonnes" id="quantity_tonnes" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="note" class="form-label">Supplementary Note</label>
                    <input type="text" name="note" id="note" class="form-control" maxlength="150" placeholder="e.g., Daily extraction" required>
                    <div id="note_feedback" class="invalid-feedback d-none">Valid characters only! Avoid special symbols.</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Production</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Quarry</th>
                    <th>Quantity (tonnes)</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productions as $production): ?>
                    <tr>
                        <td><?= htmlspecialchars($production['recorded_on']) ?></td>
                        <td><?= htmlspecialchars($production['quarry_name']) ?></td>
                        <td><?= number_format((float)$production['quantity_tonnes'], 2) ?></td>
                        <td><?= htmlspecialchars($production['note']) ?></td>
                        <td>
                            <a href="<?= APP_URL ?>/productions/edit?id=<?= $production['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <?php if (($_SESSION['role'] ?? '') === 'administrator'): ?>
                                <a href="<?= APP_URL ?>/productions/delete?id=<?= $production['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const noteInput = document.getElementById('note');
    const noteFeedback = document.getElementById('note_feedback');
    const productionForm = noteInput.closest('form');

    function setNoteInvalid(message) {
        noteInput.classList.add('is-invalid');
        noteInput.classList.remove('is-valid');
        noteFeedback.textContent = message;
        noteFeedback.classList.remove('d-none');
    }

    function clearNoteValidation() {
        noteInput.classList.remove('is-invalid');
        noteInput.classList.add('is-valid');
        noteFeedback.classList.add('d-none');
    }

    function cleanNoteValue(value) {
        return value.replace(/[^A-Za-z0-9\s\.-]/g, '').replace(/\s+/g, ' ').trim();
    }

    noteInput.addEventListener('input', () => {
        const filtered = noteInput.value.replace(/[^A-Za-z\s\.-]/g, '');
        if (noteInput.value !== filtered) {
            noteInput.value = filtered;
            setNoteInvalid('Valid characters only! Avoid special symbols.');
        } else {
            clearNoteValidation();
        }
    });

    noteInput.addEventListener('blur', () => {
        const cleaned = cleanNoteValue(noteInput.value);
        noteInput.value = cleaned;
        if (cleaned.length < 4) {
            setNoteInvalid('Note is too brief.');
        } else if (/\d/.test(cleaned)) {
            setNoteInvalid('Only letters and basic punctuation are allowed.');
        } else {
            clearNoteValidation();
        }
    });

    productionForm?.addEventListener('submit', (event) => {
        const value = cleanNoteValue(noteInput.value);
        noteInput.value = value;
        if (value.length < 4) {
            event.preventDefault();
            setNoteInvalid('Note is too brief.');
            noteInput.focus();
        } else if (/\d/.test(value)) {
            event.preventDefault();
            setNoteInvalid('Only letters and basic punctuation are allowed.');
            noteInput.focus();
        }
    });
</script>

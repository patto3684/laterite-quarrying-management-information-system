<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Customer Management</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Add New Customer</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= APP_URL ?>/customers/create">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" name="name" id="customer_name" class="form-control <?= !empty($_SESSION['form_errors']['name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_SESSION['form_old']['name'] ?? '') ?>" required>
                    <div id="customer_name_feedback" class="invalid-feedback <?= !empty($_SESSION['form_errors']['name']) ? '' : 'd-none' ?>"><?= htmlspecialchars($_SESSION['form_errors']['name'] ?? 'Customer name must be at least 3 characters long and include letters.') ?></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control <?= !empty($_SESSION['form_errors']['contact_person']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_SESSION['form_old']['contact_person'] ?? '') ?>" required>
                    <div id="contact_person_feedback" class="invalid-feedback <?= !empty($_SESSION['form_errors']['contact_person']) ? '' : 'd-none' ?>"><?= htmlspecialchars($_SESSION['form_errors']['contact_person'] ?? 'Enter first and last name using letters only.') ?></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control <?= !empty($_SESSION['form_errors']['phone']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($_SESSION['form_old']['phone'] ?? '') ?>" required>
                    <div id="phone_feedback" class="invalid-feedback <?= !empty($_SESSION['form_errors']['phone']) ? '' : 'd-none' ?>"><?= htmlspecialchars($_SESSION['form_errors']['phone'] ?? 'Phone must be 10 digits and start with 078, 079, 073, or 072.') ?></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Customer</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Contact Person</th><th>Phone</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['name']) ?></td>
                    <td><?= htmlspecialchars($customer['contact_person']) ?></td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/customers/edit?id=<?= $customer['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if (($_SESSION['role'] ?? '') === 'administrator'): ?>
                            <a href="<?= APP_URL ?>/customers/delete?id=<?= $customer['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this customer?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const customerNameInput = document.getElementById('customer_name');
    const contactPersonInput = document.getElementById('contact_person');
    const phoneInput = document.getElementById('phone');
    const feedbacks = {
        name: document.getElementById('customer_name_feedback'),
        contact: document.getElementById('contact_person_feedback'),
        phone: document.getElementById('phone_feedback'),
    };

    const validPhonePrefixes = ['078', '079', '073', '072'];

    function setInvalid(input, feedback, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        feedback.textContent = message;
        feedback.classList.remove('d-none');
    }

    function setValid(input, feedback) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        feedback.classList.add('d-none');
    }

    function cleanName(value) {
        return value.replace(/[^A-Za-z0-9\s\-&'\.]/g, '').replace(/\s+/g, ' ').trim();
    }

    function cleanPerson(value) {
        return value.replace(/[^A-Za-z\s\.\-']/g, '').replace(/\s+/g, ' ').trim();
    }

    function formatPerson(value) {
        return value
            .split(' ')
            .filter(Boolean)
            .map(part => part.charAt(0).toUpperCase() + part.slice(1).toLowerCase())
            .join(' ');
    }

    customerNameInput.addEventListener('input', () => {
        const cleaned = cleanName(customerNameInput.value);
        if (customerNameInput.value !== cleaned) {
            customerNameInput.value = cleaned;
        }
        if (cleaned.length >= 3) {
            setValid(customerNameInput, feedbacks.name);
        } else {
            customerNameInput.classList.remove('is-valid');
        }
    });

    customerNameInput.addEventListener('blur', () => {
        const value = cleanName(customerNameInput.value);
        customerNameInput.value = value;
        if (value.length < 3) {
            setInvalid(customerNameInput, feedbacks.name, 'Customer name must be at least 3 characters long.');
        } else {
            setValid(customerNameInput, feedbacks.name);
        }
    });

    contactPersonInput.addEventListener('input', () => {
        const cleaned = cleanPerson(contactPersonInput.value);
        if (contactPersonInput.value !== cleaned) {
            contactPersonInput.value = cleaned;
        }
        if (cleaned.length > 0) {
            contactPersonInput.classList.remove('is-invalid');
        }
    });

    contactPersonInput.addEventListener('blur', () => {
        const cleaned = cleanPerson(contactPersonInput.value);
        contactPersonInput.value = formatPerson(cleaned);
        const parts = contactPersonInput.value.split(' ').filter(Boolean);
        if (parts.length < 2) {
            setInvalid(contactPersonInput, feedbacks.contact, 'Enter first and last name using letters only.');
        } else {
            setValid(contactPersonInput, feedbacks.contact);
        }
    });

    phoneInput.addEventListener('input', () => {
        phoneInput.value = phoneInput.value.replace(/\D/g, '');
        if (phoneInput.value.length === 10 && validPhonePrefixes.some(prefix => phoneInput.value.startsWith(prefix))) {
            setValid(phoneInput, feedbacks.phone);
        } else {
            phoneInput.classList.remove('is-valid');
            phoneInput.classList.remove('is-invalid');
            feedbacks.phone.classList.add('d-none');
        }
    });

    phoneInput.addEventListener('blur', () => {
        const value = phoneInput.value.replace(/\D/g, '');
        phoneInput.value = value;
        if (value.length !== 10 || !validPhonePrefixes.some(prefix => value.startsWith(prefix))) {
            setInvalid(phoneInput, feedbacks.phone, 'Phone must be 10 digits and start with 078, 079, 073, or 072.');
        } else {
            setValid(phoneInput, feedbacks.phone);
        }
    });
</script>
<?php if (!empty($_SESSION['form_errors'])): ?>
    <?php unset($_SESSION['form_errors'], $_SESSION['form_old']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">System Settings</h2>
</div>
<?php if (!empty($_SESSION['notice'])): ?>
    <div class="alert alert-success mt-3"><?= htmlspecialchars($_SESSION['notice']); unset($_SESSION['notice']); ?></div>
<?php endif; ?>

<ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="user-management-tab" data-bs-toggle="tab" data-bs-target="#user-management" type="button" role="tab" aria-controls="user-management" aria-selected="true">👥 User Management</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="global-configuration-tab" data-bs-toggle="tab" data-bs-target="#global-configuration" type="button" role="tab" aria-controls="global-configuration" aria-selected="false">⚙️ Global Configuration</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="system-audit-logs-tab" data-bs-toggle="tab" data-bs-target="#system-audit-logs" type="button" role="tab" aria-controls="system-audit-logs" aria-selected="false">📜 System Audit Logs</button>
    </li>
</ul>

<div class="tab-content" id="settingsTabsContent">
    <div class="tab-pane fade show active" id="user-management" role="tabpanel" aria-labelledby="user-management-tab">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header">Create Account</div>
                    <div class="card-body">
                        <form method="post" action="<?= APP_URL ?>/settings/create_user">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input id="user_full_name" name="full_name" class="form-control" required>
                                <div id="user_full_name_feedback" class="invalid-feedback d-none">Please enter a first and last name using letters, spaces, and single hyphens only.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input id="user_email" name="email" type="email" class="form-control" required>
                                <div id="user_email_feedback" class="invalid-feedback d-none">Invalid email ending! Email must end with a valid extension like .com, .rw, or .org.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="administrator">Admin</option>
                                    <option value="finance_officer">Finance</option>
                                    <option value="quarry_officer">Quarry_officer</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header">Active Users</div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height:58vh; overflow:auto;">
                            <table class="table table-sm mb-0">
                                <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
                                <tbody>
                                <?php foreach (($users ?? []) as $u): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u['full_name']) ?></td>
                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td><?= htmlspecialchars($u['role']) ?></td>
                                        <td class="text-nowrap">
                                            <form method="post" action="<?= APP_URL ?>/settings/reset_password" style="display:inline-flex; gap:.35rem; align-items:center; margin-right:.35rem;">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <input type="password" name="new_password" class="form-control form-control-sm" placeholder="New password" style="min-width:150px;" aria-label="New password">
                                            <button class="btn btn-sm btn-outline-secondary">Reset</button>
                                        </form>
                                        <form method="post" action="<?= APP_URL ?>/settings/deactivate_user" style="display:inline-block;">
                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                            <button class="btn btn-sm btn-outline-warning" onclick="return confirm('Deactivate this account?');">Deactivate</button>
                                        </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="global-configuration" role="tabpanel" aria-labelledby="global-configuration-tab">
        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">Core System Configurations</div>
                    <div class="card-body">
                        <form id="coreSettingsForm" method="post" action="<?= APP_URL ?>/settings/save_settings">
                            <div class="row gy-3 gx-4">
                                <div class="col-md-3">
                                    <label class="form-label">Default Currency</label>
                                    <input id="default_currency" name="default_currency" maxlength="3" class="form-control" value="<?= htmlspecialchars($_SESSION['form_old']['default_currency'] ?? $settings['default_currency'] ?? 'RWF') ?>">
                                    <?php if (!empty($_SESSION['form_errors']['default_currency'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['default_currency']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Base Unit of Weight</label>
                                    <input id="base_unit_weight" name="base_unit_weight" maxlength="10" class="form-control" value="<?= htmlspecialchars($_SESSION['form_old']['base_unit_weight'] ?? $settings['base_unit_weight'] ?? 'Tons') ?>">
                                    <?php if (!empty($_SESSION['form_errors']['base_unit_weight'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['base_unit_weight']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Default VAT (%)</label>
                                    <input id="default_vat" name="default_vat" type="number" inputmode="numeric" step="1" class="form-control" value="<?= htmlspecialchars($_SESSION['form_old']['default_vat'] ?? $settings['default_vat'] ?? '18') ?>">
                                    <?php if (!empty($_SESSION['form_errors']['default_vat'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['default_vat']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Session Timeout (minutes)</label>
                                    <input id="session_timeout" name="session_timeout" type="number" inputmode="numeric" class="form-control" value="<?= htmlspecialchars($_SESSION['form_old']['session_timeout'] ?? $settings['session_timeout'] ?? '30') ?>">
                                    <?php if (!empty($_SESSION['form_errors']['session_timeout'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['session_timeout']) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="row gy-3 gx-4 mt-3">
                                <div class="col-md-4">
                                    <label class="form-label">Automated Backup Schedule</label>
                                    <select name="backup_schedule" class="form-select">
                                        <option value="daily" <?= (isset($settings['backup_schedule']) && $settings['backup_schedule']==='daily')? 'selected':'' ?>>Daily</option>
                                        <option value="weekly" <?= (isset($settings['backup_schedule']) && $settings['backup_schedule']==='weekly')? 'selected':'' ?>>Weekly</option>
                                        <option value="monthly" <?= (isset($settings['backup_schedule']) && $settings['backup_schedule']==='monthly')? 'selected':'' ?>>Monthly</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Backup Export Directory</label>
                                    <input id="backup_path" name="backup_path" class="form-control" value="<?= htmlspecialchars($_SESSION['form_old']['backup_path'] ?? $settings['backup_path'] ?? (dirname(__DIR__,2) . DIRECTORY_SEPARATOR . 'backups')) ?>">
                                    <?php if (!empty($_SESSION['form_errors']['backup_path'])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($_SESSION['form_errors']['backup_path']) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-success" type="submit">Save Core Settings</button>
                                <a class="btn btn-outline-secondary" href="<?= APP_URL ?>/settings/backup">Run Backup Now</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">System Backups</div>
                    <div class="card-body d-flex flex-column">
                        <p class="text-muted">Generate backups on-demand or use the configured schedule for automated exports. Stored copies are visible in the backup folder.</p>
                        <div class="mt-auto">
                            <a class="btn btn-outline-primary" href="<?= APP_URL ?>/settings/backup">Run Backup Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header">System Integration & API Keys</div>
                    <div class="card-body">
                        <form method="post" action="<?= APP_URL ?>/settings/save_api_key">
                            <div class="mb-3">
                                <label class="form-label">Service Name</label>
                                <input name="service" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Endpoint URL</label>
                                <input name="endpoint" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">API Key / Secret</label>
                                <input name="api_key" class="form-control">
                            </div>
                            <button class="btn btn-primary" type="submit">Save API Key</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">Stored Integration Keys</div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height:38vh; overflow:auto;">
                            <table class="table table-sm mb-0">
                                <thead class="table-light"><tr><th>Service</th><th>Endpoint</th><th>Created</th></tr></thead>
                                <tbody>
                                <?php foreach (($api_keys ?? []) as $k): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($k['service']) ?></td>
                                        <td><?= htmlspecialchars($k['endpoint']) ?></td>
                                        <td><?= htmlspecialchars($k['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="system-audit-logs" role="tabpanel" aria-labelledby="system-audit-logs-tab">
        <div class="card shadow-sm">
            <div class="card-header">System Audit Logs</div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height:68vh; overflow:auto;">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light"><tr><th>Timestamp</th><th>User</th><th>Action</th><th>Target</th><th>Details</th></tr></thead>
                        <tbody>
                        <?php foreach (($audits ?? []) as $a):
                            $detail = $a['details'] ?? ($a['detail'] ?? '');
                            $json = null;
                            if ($detail) {
                                $decoded = @json_decode($detail, true);
                                $json = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($a['created_at'] ?? '') ?></td>
                                <td><?= htmlspecialchars($a['user_name'] ?? $a['operator_name'] ?? $a['role'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($a['action'] ?? 'UNKNOWN') ?></td>
                                <td><?= htmlspecialchars($a['target_table'] ?? 'General') ?></td>
                                <td>
                                    <?php if ($json !== null): ?>
                                        <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#audit-<?= $a['id'] ?>">View</button>
                                        <div class="collapse mt-2" id="audit-<?= $a['id'] ?>"><pre class="mb-0 p-2 bg-light rounded"><?= htmlspecialchars(json_encode($json, JSON_PRETTY_PRINT)) ?></pre></div>
                                    <?php else: ?>
                                        <small class="text-muted"><?= htmlspecialchars(substr($detail,0,120)) ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('coreSettingsForm');
    if (!form) return;

    const currency = document.getElementById('default_currency');
    const weight = document.getElementById('base_unit_weight');
    const vat = document.getElementById('default_vat');
    const timeout = document.getElementById('session_timeout');
    const path = document.getElementById('backup_path');

    function setError(el, msg) {
        clearError(el);
        const wrapper = el.closest('.col-md-3, .col-md-4, .col-md-8, .col-12') || el.parentNode;
        const div = document.createElement('div');
        div.className = 'text-danger small mt-1 field-error';
        div.textContent = msg;
        wrapper.appendChild(div);
        el.classList.add('is-invalid');
    }

    function clearError(el) {
        const wrapper = el.closest('.col-md-3, .col-md-4, .col-md-8, .col-12') || el.parentNode;
        const existing = wrapper.querySelectorAll('.field-error');
        existing.forEach(e => e.remove());
        el.classList.remove('is-invalid');
    }

    // Live input enforcement
    if (currency) {
        currency.addEventListener('input', function () {
            let v = this.value.replace(/[^A-Za-z]/g, '').toUpperCase().substr(0,3);
            if (this.value !== v) this.value = v;
            clearError(this);
        });
    }

    if (weight) {
        weight.addEventListener('input', function () {
            let v = this.value.replace(/[^A-Za-z]/g, '').substr(0,10);
            if (this.value !== v) this.value = v;
            clearError(this);
        });
    }

    if (vat) {
        vat.addEventListener('input', function () {
            let v = String(this.value).replace(/\D/g, '');
            if (this.value !== v) this.value = v;
            clearError(this);
        });
    }

    if (timeout) {
        timeout.addEventListener('input', function () {
            let v = String(this.value).replace(/\D/g, '');
            if (this.value !== v) this.value = v;
            clearError(this);
        });
    }

    if (path) {
        path.addEventListener('input', function () {
            let v = this.value.replace(/[<>|?*]/g, '');
            if (this.value !== v) this.value = v;
            clearError(this);
        });
    }

    // Submit-time validation
    form.addEventListener('submit', function (e) {
        let firstInvalid = null;
        let valid = true;

        // Currency: letters only, 1-3 uppercase
        if (currency) {
            const cv = (currency.value || '').trim();
            if (!/^[A-Z]{1,3}$/.test(cv)) {
                setError(currency, 'Currency must be 1 to 3 letters (A-Z).');
                valid = false;
                firstInvalid = firstInvalid || currency;
            }
        }

        // Weight: letters only, max 10
        if (weight) {
            const wv = (weight.value || '').trim();
            if (!/^[A-Za-z]{1,10}$/.test(wv)) {
                setError(weight, 'Weight unit must contain only letters, maximum 10 characters.');
                valid = false;
                firstInvalid = firstInvalid || weight;
            }
        }

        // VAT: integer only and strictly between 0 and 100
        if (vat) {
            const vv = String(vat.value || '').trim();
            if (!/^\d+$/.test(vv) || !(parseInt(vv, 10) > 0 && parseInt(vv, 10) < 100)) {
                setError(vat, 'VAT must be an integer greater than 0 and less than 100.');
                valid = false;
                firstInvalid = firstInvalid || vat;
            }
        }

        // Timeout: whole number between 5 and 1440
        if (timeout) {
            const tv = String(timeout.value || '').trim();
            if (!/^\d+$/.test(tv) || !(parseInt(tv, 10) >= 5 && parseInt(tv, 10) <= 1440)) {
                setError(timeout, 'Session timeout must be a whole number between 5 and 1440.');
                valid = false;
                firstInvalid = firstInvalid || timeout;
            }
        }

        // Path: disallow characters < > | ? *
        if (path) {
            const pv = path.value || '';
            if (/[<>|?*]/.test(pv)) {
                setError(path, 'Backup path contains invalid characters: < > | ? *');
                valid = false;
                firstInvalid = firstInvalid || path;
            }
        }

        if (!valid) {
            e.preventDefault();
            if (firstInvalid && typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }
    });
});
</script>
<?php
if (!empty($_SESSION['form_errors'])) {
    unset($_SESSION['form_errors']);
}
if (!empty($_SESSION['form_old'])) {
    unset($_SESSION['form_old']);
}
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fullName = document.getElementById('user_full_name');
    const feedback = document.getElementById('user_full_name_feedback');
    if (!fullName) return;

    const sanitize = value => value.replace(/[^a-zA-Z\s'-]/g, '');

    const normalize = value => {
        return value
            .trim()
            .replace(/\s{2,}/g, ' ')
            .split(' ')
            .filter(Boolean)
            .map(part => part
                .split(/(-+)/).map(sub => sub.length ? sub.charAt(0).toUpperCase() + sub.slice(1).toLowerCase() : sub).join('')
            )
            .join(' ');
    };

    const validate = value => {
        const trimmed = value.trim();
        if (trimmed.length < 3) return 'Full name must be at least 3 characters.';
        if (!trimmed.includes(' ')) return 'Please include first and last name.';
        const blocks = trimmed.split(/\s+/);
        if (blocks.some(block => !/^[A-Za-z]+(?:'[A-Za-z]+)?(?:-[A-Za-z]+)?$/.test(block))) {
            return 'Names may only contain letters, single hyphens, apostrophes, and spaces.';
        }
        return '';
    };

    fullName.addEventListener('input', function () {
        const cleanValue = sanitize(this.value);
        if (cleanValue !== this.value) {
            this.value = cleanValue;
        }
        const error = validate(this.value);
        if (!error) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            if (feedback) {
                feedback.textContent = '';
                feedback.classList.add('d-none');
            }
        }
    });

    fullName.addEventListener('blur', function () {
        const formatted = normalize(this.value);
        if (formatted !== this.value) {
            this.value = formatted;
        }
        const error = validate(this.value);
        if (error) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            if (feedback) {
                feedback.textContent = error;
                feedback.classList.remove('d-none');
            }
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            if (feedback) {
                feedback.classList.add('d-none');
            }
        }
    });

    const email = document.getElementById('user_email');
    const emailFeedback = document.getElementById('user_email_feedback');
    const validateEmailExtension = value => {
        const trimmed = value.trim().toLowerCase();
        if (trimmed === '') return '';
        const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
        if (!emailPattern.test(trimmed)) {
            return 'Invalid email ending! Email must end with a valid extension like .com, .rw, or .org.';
        }
        return '';
    };

    if (email) {
        email.addEventListener('blur', function () {
            const error = validateEmailExtension(this.value);
            if (error) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                if (emailFeedback) {
                    emailFeedback.textContent = error;
                    emailFeedback.classList.remove('d-none');
                }
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                if (emailFeedback) {
                    emailFeedback.classList.add('d-none');
                }
            }
        });
    }
});
</script>


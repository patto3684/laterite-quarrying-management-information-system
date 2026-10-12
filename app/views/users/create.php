<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="mb-3">Create User</h3>
        <form method="post">
            <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label>Password</label><div class="input-group"><input type="password" name="password" class="form-control" id="createPassword" required><button class="btn btn-outline-secondary" type="button" id="toggleCreatePassword"><i class="bi bi-eye" id="createPasswordIcon"></i></button></div></div>
            <div class="mb-3"><label>Role</label><select name="role" class="form-select"><option value="administrator">Administrator</option><option value="quarry_officer">Quarry Officer</option><option value="inspector_officer">Inspector Officer</option><option value="finance_officer">Finance Officer</option><option value="leader">Leader</option></select></div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleBtn = document.getElementById('toggleCreatePassword');
        var passwordInput = document.getElementById('createPassword');
        var passwordIcon = document.getElementById('createPasswordIcon');
        if (toggleBtn && passwordInput && passwordIcon) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordIcon.classList.remove('bi-eye');
                    passwordIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    passwordIcon.classList.remove('bi-eye-slash');
                    passwordIcon.classList.add('bi-eye');
                }
            });
        }
    });
</script>
</div>

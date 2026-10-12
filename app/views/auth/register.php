<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | LQMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="mb-3">Create Account</h3>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="administrator">Administrator</option>
                                <option value="quarry_officer">Quarry Officer</option>
                                <option value="inspector_officer">Inspector Officer</option>
                                <option value="finance_officer">Finance Officer</option>
                                <option value="leader">Leader</option>
                            </select>
                        </div>
                        <button class="btn btn-primary">Register</button>
                        <a href="<?= APP_URL ?>/login" class="btn btn-outline-secondary ms-2">Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
require_once __DIR__ . '/../config/database.php';

$pdo = getConnection();

$email = getenv('SEED_ADMIN_EMAIL') ?: 'admin@lqsmis.com';
$password = getenv('SEED_ADMIN_PASSWORD') ?: 'ChangeMe123!';
$fullName = getenv('SEED_ADMIN_NAME') ?: 'System Administrator';
$role = getenv('SEED_ADMIN_ROLE') ?: 'admin';

// Check if user exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "Admin user already exists: {$email}\n";
    exit(0);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role, created_at, is_active) VALUES (?, ?, ?, ?, NOW(), 1)');
$stmt->execute([$fullName, $email, $hash, $role]);

echo "Created admin user: {$email}\n";

<?php
/**
 * Admin Console for RBAC Management
 * Allows administrators to view and modify role permissions
 * 
 * Usage:
 * Place in your admin panel or accessible through a utility page
 */

// Example functions for admin use:

/**
 * Get all roles with their permissions
 */
function getAllRolesWithPermissions() {
    $roleModel = new RolePermission();
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    $query = "
        SELECT 
            r.id, r.name as role_name, r.description,
            GROUP_CONCAT(p.name) as permissions
        FROM roles r
        LEFT JOIN role_permissions rp ON r.id = rp.role_id
        LEFT JOIN permissions p ON rp.permission_id = p.id
        GROUP BY r.id, r.name, r.description
    ";
    
    $result = $db->query($query);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get all available permissions
 */
function getAllPermissions() {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    $query = "SELECT id, name, description FROM permissions ORDER BY name";
    $result = $db->query($query);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Grant a permission to a role
 */
function grantPermissionToRole($roleName, $permissionName) {
    $roleModel = new RolePermission();
    return $roleModel->addPermissionToRole($roleName, $permissionName);
}

/**
 * Revoke a permission from a role
 */
function revokePermissionFromRole($roleName, $permissionName) {
    $roleModel = new RolePermission();
    return $roleModel->removePermissionFromRole($roleName, $permissionName);
}

/**
 * Create a new role
 */
function createRole($roleName, $description = null) {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    $query = "INSERT INTO roles (name, description) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    return $stmt->execute([$roleName, $description]);
}

/**
 * Create a new permission
 */
function createPermission($permissionName, $description = null) {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    $query = "INSERT INTO permissions (name, description) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    return $stmt->execute([$permissionName, $description]);
}

// Example CLI usage:
// php admin_console.php grant quarry_officer finance
// php admin_console.php revoke quarry_officer finance
// php admin_console.php list-roles

if (php_sapi_name() === 'cli' && isset($argv[1])) {
    $command = $argv[1];
    
    switch ($command) {
        case 'list-roles':
            $roles = getAllRolesWithPermissions();
            echo "=== System Roles and Permissions ===\n\n";
            foreach ($roles as $role) {
                echo "Role: {$role['role_name']}\n";
                echo "Description: {$role['description']}\n";
                echo "Permissions: " . ($role['permissions'] ? $role['permissions'] : 'None') . "\n";
                echo "---\n";
            }
            break;
            
        case 'grant':
            if (!isset($argv[2]) || !isset($argv[3])) {
                echo "Usage: php admin_console.php grant <role_name> <permission_name>\n";
                exit(1);
            }
            $result = grantPermissionToRole($argv[2], $argv[3]);
            echo $result ? "Permission granted.\n" : "Failed to grant permission.\n";
            break;
            
        case 'revoke':
            if (!isset($argv[2]) || !isset($argv[3])) {
                echo "Usage: php admin_console.php revoke <role_name> <permission_name>\n";
                exit(1);
            }
            $result = revokePermissionFromRole($argv[2], $argv[3]);
            echo $result ? "Permission revoked.\n" : "Failed to revoke permission.\n";
            break;
            
        default:
            echo "Available commands:\n";
            echo "  list-roles                    - Show all roles and permissions\n";
            echo "  grant <role> <permission>     - Grant a permission to a role\n";
            echo "  revoke <role> <permission>    - Revoke a permission from a role\n";
    }
}

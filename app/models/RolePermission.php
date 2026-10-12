<?php
class RolePermission extends Model {
    protected string $table = 'role_permissions';

    /**
     * Get all permissions for a specific role
     *
     * @param string $roleName Role name (e.g., 'administrator', 'quarry_officer')
     * @return array Array of permission names
     */
    public function getPermissionsByRole(string $roleName): array {
        $query = "
            SELECT p.name 
            FROM permissions p
            INNER JOIN role_permissions rp ON p.id = rp.permission_id
            INNER JOIN roles r ON rp.role_id = r.id
            WHERE r.name = ?
        ";
        
        $result = $this->db->prepare($query);
        $result->execute([$roleName]);
        
        $permissions = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $permissions[] = $row['name'];
        }
        
        return $permissions;
    }

    /**
     * Check if a role has a specific permission
     *
     * @param string $roleName Role name
     * @param string $permissionName Permission name
     * @return bool
     */
    public function hasPermission(string $roleName, string $permissionName): bool {
        $query = "
            SELECT 1 FROM role_permissions rp
            INNER JOIN roles r ON rp.role_id = r.id
            INNER JOIN permissions p ON rp.permission_id = p.id
            WHERE r.name = ? AND p.name = ?
            LIMIT 1
        ";
        
        $result = $this->db->prepare($query);
        $result->execute([$roleName, $permissionName]);
        
        return $result->rowCount() > 0;
    }

    /**
     * Add a permission to a role
     *
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function addPermissionToRole(string $roleName, string $permissionName): bool {
        // Get role and permission IDs
        $roleQuery = $this->db->prepare("SELECT id FROM roles WHERE name = ?");
        $roleQuery->execute([$roleName]);
        $role = $roleQuery->fetch(PDO::FETCH_ASSOC);
        
        $permQuery = $this->db->prepare("SELECT id FROM permissions WHERE name = ?");
        $permQuery->execute([$permissionName]);
        $perm = $permQuery->fetch(PDO::FETCH_ASSOC);
        
        if (!$role || !$perm) {
            return false;
        }
        
        // Insert the permission
        $insertQuery = $this->db->prepare("
            INSERT INTO role_permissions (role_id, permission_id) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE id=id
        ");
        
        return $insertQuery->execute([$role['id'], $perm['id']]);
    }

    /**
     * Remove a permission from a role
     *
     * @param string $roleName
     * @param string $permissionName
     * @return bool
     */
    public function removePermissionFromRole(string $roleName, string $permissionName): bool {
        $query = "
            DELETE FROM role_permissions 
            WHERE role_id = (SELECT id FROM roles WHERE name = ?)
            AND permission_id = (SELECT id FROM permissions WHERE name = ?)
        ";
        
        $result = $this->db->prepare($query);
        return $result->execute([$roleName, $permissionName]);
    }
}

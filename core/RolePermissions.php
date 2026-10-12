<?php
/**
 * Role-Based Access Control (RBAC) Configuration
 * Database-driven: Permissions are stored in the database and loaded dynamically
 */
class RolePermissions {
    private static ?RolePermission $model = null;

    /**
     * Initialize the RolePermission model
     */
    private static function initModel(): void {
        if (self::$model === null) {
            self::$model = new RolePermission();
        }
    }

    /**
     * Check if a role has permission to access a page
     *
     * @param string $role User role
     * @param string $page Page identifier
     * @return bool
     */
    public static function hasAccess(string $role, string $page): bool {
        self::initModel();
        return self::$model->hasPermission($role, $page);
    }

    /**
     * Get all pages accessible by a role
     *
     * @param string $role User role
     * @return array
     */
    public static function getAccessiblePages(string $role): array {
        self::initModel();
        return self::$model->getPermissionsByRole($role);
    }

    /**
     * Get menu items for a specific role
     * Maps pages to menu display data
     *
     * @param string $role User role
     * @return array
     */
    public static function getMenuItems(string $role): array {
        $allMenuItems = [
            'dashboard' => ['url' => '/dashboard', 'label' => 'Dashboard'],
            'quarries' => ['url' => '/quarries', 'label' => 'Quarries'],
            'productions' => ['url' => '/productions', 'label' => 'Production'],
            'stocks' => ['url' => '/stocks', 'label' => 'Stock'],
            'equipment' => ['url' => '/equipment', 'label' => 'Equipment'],
            'finance' => ['url' => '/finance', 'label' => 'Finance'],
            'inspections' => ['url' => '/inspections', 'label' => 'Inspections'],
            'customers' => ['url' => '/customers', 'label' => 'Customers'],
            'submissions' => ['url' => '/submissions', 'label' => 'Submissions'],
            'reports' => ['url' => '/reports', 'label' => 'Reports'],
            'users' => ['url' => '/users', 'label' => 'Users'],
            'settings' => ['url' => '/settings', 'label' => 'Settings'],
        ];

        $accessiblePages = self::getAccessiblePages($role);
        $menu = [];

        foreach ($accessiblePages as $page) {
            if (isset($allMenuItems[$page])) {
                $menu[$page] = $allMenuItems[$page];
            }
        }

        return $menu;
    }

    /**
     * Add a new permission to a role (admin utility)
     *
     * @param string $role Role name
     * @param string $page Page/permission name
     * @return bool
     */
    public static function addPermission(string $role, string $page): bool {
        self::initModel();
        return self::$model->addPermissionToRole($role, $page);
    }

    /**
     * Remove a permission from a role (admin utility)
     *
     * @param string $role Role name
     * @param string $page Page/permission name
     * @return bool
     */
    public static function removePermission(string $role, string $page): bool {
        self::initModel();
        return self::$model->removePermissionFromRole($role, $page);
    }
}

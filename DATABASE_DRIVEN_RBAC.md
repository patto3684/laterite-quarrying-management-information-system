# Database-Driven RBAC System - Complete Guide

## Overview

LQSMIS now uses a **professional, database-driven Role-Based Access Control (RBAC)** system. This means:

✓ **No code changes needed** to modify permissions  
✓ **Dynamic role management** - add/remove permissions via database  
✓ **Scalable architecture** - designed for growth  
✓ **Same interface** - controllers work exactly as before  

---

## System Architecture

### Database Schema

**3 new tables manage all permissions:**

```sql
roles
├── id (INT)
├── name (VARCHAR) - Unique role identifier
└── description (VARCHAR)

permissions
├── id (INT)
├── name (VARCHAR) - Unique permission identifier
└── description (VARCHAR)

role_permissions (Junction table)
├── id (INT)
├── role_id (FK to roles)
└── permission_id (FK to permissions)
```

### Default Roles

| Role | Permissions |
|------|------------|
| **administrator** | ALL (dashboard, quarries, productions, stocks, equipment, finance, inspections, customers, reports, users, settings) |
| **quarry_officer** | dashboard, quarries, productions, stocks, equipment, inspections, reports |
| **inspector_officer** | dashboard, inspections, quarries, reports |
| **finance_officer** | dashboard, finance, customers, reports |

---

## How It Works

### 1. **Application Layer** (Controllers)
```php
class ProductionController extends Controller {
    public function index(): void {
        $this->requireAccess('productions');  // Check permission
        // ... show production data
    }
}
```

### 2. **Business Logic Layer** (RolePermissions)
```php
RolePermissions::hasAccess('quarry_officer', 'productions')
// Queries database and returns: true or false
```

### 3. **Data Layer** (RolePermission Model)
```php
$model = new RolePermission();
$model->getPermissionsByRole('quarry_officer')
// Returns: ['dashboard', 'quarries', 'productions', ...]
```

### 4. **Database Layer** (SQL Query)
```sql
SELECT p.name FROM permissions p
INNER JOIN role_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON rp.role_id = r.id
WHERE r.name = 'quarry_officer'
```

---

## Managing Roles & Permissions

### Using Admin Console (CLI)

```bash
# View all roles and permissions
php admin_console.php list-roles

# Grant a permission to a role
php admin_console.php grant quarry_officer finance

# Revoke a permission from a role
php admin_console.php revoke quarry_officer finance
```

### Using SQL Directly

**Add a permission to a role:**
```sql
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'quarry_officer' AND p.name = 'finance'
ON DUPLICATE KEY UPDATE id=id;
```

**Remove a permission from a role:**
```sql
DELETE FROM role_permissions 
WHERE role_id = (SELECT id FROM roles WHERE name = 'quarry_officer')
AND permission_id = (SELECT id FROM permissions WHERE name = 'finance');
```

### Creating a New Role

```sql
-- 1. Create the role
INSERT INTO roles (name, description) 
VALUES ('supervisor', 'Site supervisor with operational oversight');

-- 2. Assign permissions
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'supervisor' 
AND p.name IN ('dashboard', 'quarries', 'productions', 'equipment');

-- 3. Create a user with that role
INSERT INTO users (full_name, email, password_hash, role)
VALUES ('John Supervisor', 'supervisor@lqsmis.com', '$2y$10$...', 'supervisor');
```

### Creating a New Permission

```sql
-- 1. Create the permission
INSERT INTO permissions (name, description)
VALUES ('maintenance', 'Manage maintenance records');

-- 2. Assign to one or more roles
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name IN ('administrator', 'quarry_officer')
AND p.name = 'maintenance';
```

---

## File Structure

### New/Modified Files

```
core/
├── RolePermissions.php (UPDATED - now database-driven)
└── Controller.php (UPDATED - added requireAccess method)

app/models/
└── RolePermission.php (NEW - database queries)

app/views/
└── layout.php (UPDATED - uses database permissions)

setup.sql (UPDATED - added RBAC tables)

admin_console.php (NEW - CLI utility)

RBAC_DOCUMENTATION.md (EXISTING - still valid)
```

---

## Implementation Details

### RolePermission Model

```php
$rolePermModel = new RolePermission();

// Get all permissions for a role
$perms = $rolePermModel->getPermissionsByRole('quarry_officer');
// Returns: ['dashboard', 'quarries', 'productions', ...]

// Check single permission
$has = $rolePermModel->hasPermission('quarry_officer', 'finance');
// Returns: bool

// Add permission
$success = $rolePermModel->addPermissionToRole('quarry_officer', 'finance');

// Remove permission
$success = $rolePermModel->removePermissionFromRole('quarry_officer', 'finance');
```

### RolePermissions (Static Facade)

```php
// These methods work the same as before, but query the database
RolePermissions::hasAccess($role, $page);
RolePermissions::getAccessiblePages($role);
RolePermissions::getMenuItems($role);

// New admin methods
RolePermissions::addPermission($role, $page);
RolePermissions::removePermission($role, $page);
```

### Controller Usage

```php
class SomeController extends Controller {
    public function index(): void {
        $this->requireAccess('some_page');
        // If user doesn't have permission: HTTP 403 & exit
        // Otherwise: continue normally
    }
}
```

---

## Advantages of This Approach

| Aspect | Before | After |
|--------|--------|-------|
| **Add new role** | Edit code, redeploy | SQL INSERT, done |
| **Change permissions** | Edit code, redeploy | SQL UPDATE, instant |
| **Security audit** | Check source code | Query database |
| **Scaling** | Manual process | Automated, flexible |
| **Maintenance** | Error-prone | Database transactions |
| **Performance** | Array lookups | Cached via sessions |

---

## Troubleshooting

### User can't access a page they should be able to

1. Check the database:
```sql
SELECT p.name FROM permissions p
INNER JOIN role_permissions rp ON p.id = rp.permission_id
INNER JOIN roles r ON rp.role_id = r.id
WHERE r.name = 'their_role';
```

2. If permission is missing, add it:
```sql
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'their_role' AND p.name = 'page_name'
ON DUPLICATE KEY UPDATE id=id;
```

### User is seeing unauthorized menu items

1. Clear the PHP session cache
2. User should log out and log back in
3. The menu is dynamically generated from database on each request

### New role not working

1. Verify the role exists:
```sql
SELECT * FROM roles WHERE name = 'new_role';
```

2. Verify role_permissions are created:
```sql
SELECT * FROM role_permissions WHERE role_id = (SELECT id FROM roles WHERE name = 'new_role');
```

3. Ensure user's `role` column matches the database role name exactly (case-sensitive)

---

## Migration Notes

### From Old System to New

If you were using the old hardcoded `RolePermissions` class:

- **No changes needed in controllers** - interface is identical
- **Permissions are now database-sourced** - old array no longer used
- **Database is pre-populated** during `setup.sql` execution
- **Roles must match exactly** between `users.role` and `roles.name`

### Performance

- **First query per request** caches in `$_SESSION['user_id']`
- **Menu generation** queries database (minimal overhead)
- **Access checks** query once per page load (via `requireAccess()`)
- **Consider caching** roles/permissions if you have 1000+ users

---

## Security Notes

✓ **Backend validation** - Controller checks permissions, not just UI  
✓ **Database constraints** - Foreign keys prevent orphaned records  
✓ **Unique permissions** - Role+permission combination is unique  
✓ **SQL injection protected** - Using prepared statements  
✓ **Session-based** - Permissions checked from authenticated session  

---

## Next Steps

1. **Run setup.sql** to create the new tables
2. **Test with different roles** - try quarry_officer, inspector_officer, finance_officer
3. **Use admin_console.php** to manage permissions as needed
4. **Add custom roles** when business requirements evolve

Enjoy your new professional RBAC system!

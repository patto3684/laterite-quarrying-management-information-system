# Role-Based Access Control (RBAC) Documentation

## System Roles

LQSMIS has 4 defined user roles, each with specific permissions:

### 1. Administrator
**Full system access to all features**

| Module | Access |
|--------|--------|
| Dashboard | ✓ |
| Quarries | ✓ |
| Production | ✓ |
| Stock | ✓ |
| Equipment | ✓ |
| Finance | ✓ |
| Inspections | ✓ |
| Customers | ✓ |
| Reports | ✓ |
| Users | ✓ |
| Settings | ✓ |

**Default Admin Account:**
- Email: admin@lqsmis.com
- Password: (set during installation)

---

### 2. Quarry Officer
**Manages quarry operations, production, and equipment**

| Module | Access |
|--------|--------|
| Dashboard | ✓ |
| Quarries | ✓ |
| Production | ✓ |
| Stock | ✓ |
| Equipment | ✓ |
| Inspections | ✓ |
| Reports | ✓ |
| Finance | ✗ |
| Customers | ✗ |
| Users | ✗ |
| Settings | ✗ |

**Default Account:**
- Email: officer@lqsmis.com
- Password: (set during installation)

---

### 3. Inspector Officer
**Handles compliance, safety, and quarry inspections**

| Module | Access |
|--------|--------|
| Dashboard | ✓ |
| Inspections | ✓ |
| Quarries | ✓ |
| Reports | ✓ |
| Production | ✗ |
| Stock | ✗ |
| Equipment | ✗ |
| Finance | ✗ |
| Customers | ✗ |
| Users | ✗ |
| Settings | ✗ |

**Default Account:**
- Email: inspector@lqsmis.com
- Password: (set during installation)

---

### 4. Finance Officer
**Manages financial transactions, customers, and revenue**

| Module | Access |
|--------|--------|
| Dashboard | ✓ |
| Finance | ✓ |
| Customers | ✓ |
| Reports | ✓ |
| Quarries | ✗ |
| Production | ✗ |
| Stock | ✗ |
| Equipment | ✗ |
| Inspections | ✗ |
| Users | ✗ |
| Settings | ✗ |

**Default Account:**
- Email: finance@lqsmis.com
- Password: (set during installation)

---

## How RBAC Works

### Navigation Menu
The sidebar menu is **dynamically filtered** based on user role. Users see only the pages they have permission to access.

```php
// Example: Role-based menu filtering
$menuItems = RolePermissions::getMenuItems($_SESSION['role']);
foreach ($menuItems as $page => $item) {
    // Display only permitted menu items
}
```

### Access Control in Controllers
Each controller's action method checks if the user has access before allowing the request:

```php
class DashboardController extends Controller {
    public function index(): void {
        $this->requireAccess('dashboard');  // Verify access
        // ... controller logic
    }
}
```

### Access Denial
If a user tries to access a page they don't have permission for:
- Browser shows: **"Access Denied"** (HTTP 403)
- Controller prevents execution

---

## Adding New Roles

To add a new role (e.g., "Supervisor"):

1. **Update RolePermissions.php:**
```php
'supervisor' => [
    'dashboard',
    'quarries',
    'productions',
    'inspections',
]
```

2. **Create default user in setup.sql:**
```sql
INSERT INTO users (full_name, email, password_hash, role)
SELECT 'Supervisor', 'supervisor@lqsmis.com', '$2y$10$...', 'supervisor'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'supervisor@lqsmis.com');
```

3. **Database column already supports any role value** (VARCHAR 50)

---

## Modifying Permissions

To give a role access to new features:

1. Edit `core/RolePermissions.php`
2. Add the page name to the role's permission array:

```php
'quarry_officer' => [
    'dashboard',
    'quarries',
    'productions',
    'stocks',
    'equipment',
    'inspections',
    'reports',
    'finance',  // Added Finance access
]
```

---

## Default Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Administrator | admin@lqsmis.com | password |
| Quarry Officer | officer@lqsmis.com | password |
| Inspector Officer | inspector@lqsmis.com | password |
| Finance Officer | finance@lqsmis.com | password |

*(All accounts use the same hashed password from setup.sql)*

---

## Security Implementation

✓ **Menu filtering** - Users don't see unauthorized menu items  
✓ **Controller validation** - Backend enforces access checks  
✓ **Session storage** - Role stored in `$_SESSION['role']`  
✓ **Error handling** - HTTP 403 on unauthorized access  
✓ **Consistent checks** - All controllers use `requireAccess()`

---

## Testing Access Control

To verify RBAC is working:

1. Login as **Quarry Officer**
2. Notice the sidebar only shows: Dashboard, Quarries, Production, Stock, Equipment, Inspections, Reports
3. Users and Settings menu items should be **hidden**
4. If you try to visit `/users` directly, you get "Access Denied"

Try this with each role to verify the menu filtering works correctly.
x
# Role-Based Access Control (RBAC)

The Employee Management System implements basic role-based access control with three user roles.

## Roles and Permissions

### Admin
- **Full Access**: Create, read, update, delete employees and departments
- **Restore Access**: Can restore deleted items from trash
- **Force Delete**: Can permanently delete items

### Manager  
- **Create/Update**: Can create and modify employees and departments
- **Delete**: Can soft delete (move to trash) employees and departments
- **No Restore**: Cannot restore items or access trash
- **No Force Delete**: Cannot permanently delete items

### Viewer
- **Read-Only**: Can only view employees and departments
- **No Create**: Cannot create items
- **No Delete**: Cannot delete items
- **No Restore**: Cannot restore items

## Permission Matrix

| Action | Admin | Manager | Viewer |
|--------|-------|---------|--------|
| View Employees | ✓ | ✓ | ✓ |
| Create Employee | ✓ | ✓ | ✗ |
| Edit Employee | ✓ | ✓ | ✗ |
| Delete Employee (Soft) | ✓ | ✓ | ✗ |
| View Trash | ✓ | ✗ | ✗ |
| Restore Employee | ✓ | ✗ | ✗ |
| Force Delete Employee | ✓ | ✗ | ✗ |
| View Departments | ✓ | ✓ | ✓ |
| Create Department | ✓ | ✓ | ✗ |
| Edit Department | ✓ | ✓ | ✗ |
| Delete Department (Soft) | ✓ | ✓ | ✗ |
| Restore Department | ✓ | ✗ | ✗ |
| Force Delete Department | ✓ | ✗ | ✗ |
| Import Employees | ✓ | ✓ | ✗ |
| Export Data | ✓ | ✓ | ✓ |
| View Activity Log | ✓ | ✓ | ✓ |

## User Methods

The `User` model provides helper methods for role checking:

```php
$user = auth()->user();

// Check role
$user->isAdmin();           // Returns boolean
$user->isManager();         // Returns boolean
$user->isViewer();          // Returns boolean

// Check if user has role(s)
$user->hasRole('admin');    // Single role
$user->hasRole(['admin', 'manager']);  // Multiple roles

// Check permissions
$user->canDelete();         // Admin or Manager
$user->canRestore();        // Admin only
```

## Usage in Controllers

```php
// Check before deleting
if (!auth()->user()->canDelete()) {
    return redirect()->back()->with('error', 'Permission denied');
}

// Check for specific role
if (!auth()->user()->isAdmin()) {
    return redirect()->back()->with('error', 'Admin only');
}

// Check multiple roles
if (!auth()->user()->hasRole(['admin', 'manager'])) {
    return redirect()->back()->with('error', 'Permission denied');
}
```

## Database Schema

The users table includes a `role` column:

```sql
ALTER TABLE users ADD COLUMN role ENUM('admin', 'manager', 'viewer') DEFAULT 'viewer';
```

## Default Users

When the application is seeded, three default users are created:

1. **Admin User**
   - Email: admin@example.com
   - Password: password
   - Role: admin

2. **Manager User**
   - Email: test@example.com
   - Password: password
   - Role: manager

3. **Viewer User**
   - Email: viewer@example.com
   - Password: password
   - Role: viewer

## Future Enhancements

Potential improvements to the RBAC system:

- Database roles table for more granular control
- Permission-based system (instead of role-based)
- Department-level role assignments
- Role-based activity log visibility
- UI elements hidden based on user role

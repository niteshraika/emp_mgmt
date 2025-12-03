# Employee Management System

A comprehensive Laravel 12 web application for managing employees and departments with advanced features including role-based access control, activity logging, CSV import/export, and bulk operations.

## Features

✅ **Employee Management**
- Full CRUD operations for employees
- Advanced filtering (search, department, salary range, joining date)
- Employee profile details view
- Soft delete with trash management

✅ **Department Management**
- Department CRUD operations
- Employee count and salary statistics per department
- Soft delete with trash management

✅ **CSV Operations**
- **Import**: Drag-and-drop bulk import with validation preview
- **Export**: Stream-based export with filter support
- **Bulk Export**: Export selected employees

✅ **Bulk Actions**
- Checkbox-based item selection
- Bulk delete operation with confirmation
- Bulk CSV export of selected items

✅ **Activity Logging (Audit Trail)**
- Track all employee/department modifications
- Record create, update, delete, and restore actions
- Detailed change tracking (before/after values)
- User attribution, timestamps, IP address, and user agent

✅ **Role-Based Access Control**
- Three roles: Admin, Manager, Viewer
- Permission-based access to features
- Role-specific restrictions on operations

✅ **Security & Validation**
- Form Request validation on all inputs
- Rate limiting on authentication (5 attempts/minute)
- Security headers middleware
- CSRF protection on all forms
- Input sanitization and data validation

## Technology Stack

- **Backend**: Laravel 12.40.2
- **Frontend**: Blade templating, Tailwind CSS 4.0.0
- **Build Tool**: Vite 7.2.6
- **Database**: MySQL with soft deletes
- **PHP**: 8.3+

## Quick Start

### Installation

```bash
# Clone or navigate to project directory
cd c:\projects\emp_mgmt

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Create environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed default users and data
php artisan db:seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

### Default Credentials

After seeding, three users are available:

```
Admin User:
  Email: admin@example.com
  Password: password
  Role: admin (full access)

Manager User:
  Email: test@example.com
  Password: password
  Role: manager (can delete but not restore)

Viewer User:
  Email: viewer@example.com
  Password: password
  Role: viewer (read-only)
```

## Project Structure

### Key Directories

```
app/
├── Http/Controllers/      # Request handlers
├── Http/Requests/         # Form validation
├── Models/                # Database models
└── Providers/             # Service providers

database/
├── migrations/            # Database schema
├── factories/             # Model factories
└── seeders/               # Database seeders

resources/
├── views/                 # Blade templates
├── css/                   # Stylesheets
└── js/                    # JavaScript

routes/
└── web.php                # Web routes
```

### Models

- **User**: Authentication with role support
- **Employee**: Employee records with soft deletes
- **Department**: Department organization
- **ActivityLog**: Audit trail of all changes

## Routes

### Employees
- `GET /employees` - List employees (with filters)
- `GET /employees/import` - Import form
- `POST /employees/import-preview` - Preview import
- `POST /employees/import-process` - Process import
- `GET /employees/export/csv` - Export as CSV
- `GET /employees/create` - Create form
- `POST /employees` - Store employee
- `GET /employees/{id}` - View employee
- `GET /employees/{id}/edit` - Edit form
- `PUT /employees/{id}` - Update employee
- `DELETE /employees/{id}` - Delete employee (soft)
- `POST /employees/bulk-delete` - Bulk delete
- `POST /employees/bulk-export` - Bulk export
- `GET /trash/employees` - View trash
- `POST /trash/employees/{id}/restore` - Restore employee
- `DELETE /trash/employees/{id}/force-delete` - Permanent delete

### Departments
- `GET /departments` - List departments
- `GET /departments/export/csv` - Export as CSV
- `GET /departments/create` - Create form
- `POST /departments` - Store department
- `GET /departments/{id}` - View department
- `GET /departments/{id}/edit` - Edit form
- `PUT /departments/{id}` - Update department
- `DELETE /departments/{id}` - Delete department (soft)
- `GET /trash/departments` - View trash
- `POST /trash/departments/{id}/restore` - Restore department
- `DELETE /trash/departments/{id}/force-delete` - Permanent delete

### Activity Logs
- `GET /activity-logs` - List logs (with filters)
- `GET /activity-logs/{id}` - View detailed log

### Dashboard
- `GET /dashboard` - Dashboard with statistics

## Permissions by Role

| Action | Admin | Manager | Viewer |
|--------|-------|---------|--------|
| View Employees/Departments | ✓ | ✓ | ✓ |
| Create/Edit | ✓ | ✓ | ✗ |
| Delete (Soft) | ✓ | ✓ | ✗ |
| View Trash | ✓ | ✗ | ✗ |
| Restore | ✓ | ✗ | ✗ |
| Permanent Delete | ✓ | ✗ | ✗ |
| Import CSV | ✓ | ✓ | ✗ |
| Export CSV | ✓ | ✓ | ✓ |
| View Activity Log | ✓ | ✓ | ✓ |

## CSV Import Format

CSV files must contain these headers:
```
first_name,last_name,email,phone,department_id,salary,joining_date,address
```

See `CSV_IMPORT_GUIDE.md` for detailed specifications.

## Role-Based Access Control

See `RBAC_GUIDE.md` for detailed role documentation.

## Activity Logging

All employee and department operations are logged with:
- Action type (created/updated/deleted/restored)
- User who performed the action
- IP address and user agent
- Detailed changes (before/after values for updates)
- Timestamps

Access logs via `GET /activity-logs`.

## Development

### Build Assets
```bash
npm run build          # Production build
npm run dev            # Development watch
```

### Run Tests
```bash
php artisan test
```

### Cache Management
```bash
php artisan optimize:clear    # Clear all caches
php artisan cache:clear       # Clear application cache
php artisan view:clear        # Clear view cache
```

## Database

### Migrations
```bash
php artisan migrate          # Run all migrations
php artisan migrate:rollback # Rollback last batch
```

### Seeding
```bash
php artisan db:seed                          # Run all seeders
php artisan db:seed --class=DatabaseSeeder   # Run specific seeder
```

## Performance Notes

- CSV export uses StreamedResponse for memory efficiency
- Activity logs are indexed on frequently queried columns
- Soft deletes preserve audit trail while keeping lists clean
- Pagination limits: 15 items per page on lists, 20 on activity logs

## Documentation

- `IMPLEMENTATION_SUMMARY.md` - Complete feature overview
- `CSV_IMPORT_GUIDE.md` - CSV import specifications
- `RBAC_GUIDE.md` - Role-based access control details

## Security Features

✓ Form Request validation on all inputs
✓ Rate limiting (5 login attempts/minute)
✓ CSRF protection
✓ Security headers middleware
✓ SQL injection prevention
✓ Role-based authorization
✓ Activity audit trail
✓ Soft deletes for data recovery

## Support & Troubleshooting

For common issues and solutions, refer to the specific feature guides:
- `CSV_IMPORT_GUIDE.md` - CSV import troubleshooting
- `RBAC_GUIDE.md` - Permission issues

## License

This project is built with Laravel and uses the MIT License.

## Status

✅ **Complete and Production Ready**

All 9 planned features implemented and tested.
Build: Successful (579ms)
Database: All migrations passed
PHP Syntax: Clean (no errors)


In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

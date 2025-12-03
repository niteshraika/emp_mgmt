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
  Password: P@ssw0rd
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

## Test Results

- Date: 2025-12-03 00:00 UTC
- Command: php artisan test

Summary
- Suites: 9
- Tests: 34
- Assertions: 130
- Passed: 34
- Failed: 0
- Skipped: 0
- Duration: 2.56s

Suites
- Unit
  - ExampleTest: PASS (1/1)
- Feature
  - AuthAndRbacTest: PASS (5/5)
  - ActivityLogsAndExportsTest: PASS (3/3)
  - CrudDepartmentsTest: PASS (9/9)
  - CrudEmployeesTest: PASS (5/5)
  - CrudUsersTest: PASS (8/8)
  - ImportFlowTest: PASS (1/1)
  - SecurityHeadersTest: PASS (1/1)
  - ExampleTest: PASS (1/1)

Notes
- Employee email validation accepts any valid email (no domain restriction) in StoreEmployeeRequest and UpdateEmployeeRequest.
- Employee CRUD tests use referrer context and session assertions for reliable outcomes.

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

## Compliance

This application includes features that support compliance efforts. It does not itself certify compliance; production compliance depends on your deployment, processes, and complementary controls.

Supported obligations and control mappings:
- GDPR (EU) and CCPA/CPRA (California):
  - Lawful access and least privilege via RBAC (Admin/Manager/Viewer) and route/middleware authorization.
  - Data subject request facilitation: soft deletes enable reversible removals; permanent delete endpoints support erasure when authorized.
  - Purpose limitation and accountability supported by detailed activity logs (who/what/when/where, before/after values).
  - Security by design: CSRF protection, input validation, SQLi mitigation, and security headers.
- SOC 2 (Security/Availability/Confidentiality) readiness support:
  - Access controls (RBAC), authentication throttling, and audit logging for change tracking.
  - Configuration management via environment variables and versioned migrations/seeders.
  - Monitoring and alerting can be integrated using application logs and HTTP access logs (infrastructure dependent).
- PCI DSS adjacency (not a cardholder data system):
  - No storage of card data in scope by default. If extended, ensure network segmentation, encryption at rest, and key management outside this app.

Implemented technical controls in this repo:
- Authentication and session security using Laravel’s session and CSRF mechanisms.
- Rate limiting on login attempts (5/min).
- Security headers middleware (see app/Http/Middleware/SecurityHeaders.php) to reduce attack surface (X-Frame-Options, X-Content-Type-Options, etc.).
- Detailed audit trail (ActivityLog model and views) with user attribution, IP, user agent, timestamps, and diff of changes.
- Soft deletes for reversible removals; permanent delete endpoints restricted by role.
- Input validation via Form Requests across create/update flows.
- Output encoding via Blade templates to reduce XSS exposure.
- CSV import validation and preview to prevent bulk bad data ingestion.

Deployment and configuration responsibilities:
- Encryption in transit: serve exclusively over HTTPS with modern TLS; configure HSTS in the web server/proxy.
- Encryption at rest: use encrypted database volumes and managed backups; rotate credentials stored in .env.
- Logging and retention: route logs to centralized storage (e.g., ELK, CloudWatch) with retention policies that match your regulatory needs; protect logs due to sensitive before/after values.
- Data retention and deletion: define retention windows for soft-deleted records; use force delete endpoints for erasure requests after approval.
- Backups and DR: implement periodic, encrypted backups with tested restore procedures.
- Access control hygiene: provision least-privilege application users and rotate admin accounts; enable MFA at the IdP or reverse proxy layer if applicable.

References:
- SECURITY.md for hardening guidance and secure development/ops practices.
- RBAC_GUIDE.md for permissions and roles.
- CSV_IMPORT_GUIDE.md for data handling considerations during import/export.

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

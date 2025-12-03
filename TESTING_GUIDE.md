# Testing Guide

This document explains the automated Feature tests for CRUD operations and how to run them.

## Scope

Feature tests cover:
- Users (admin-only): list, create (RBAC checks)
- Departments: list, create, show, edit, update, soft-delete, trash, restore (admin), force-delete (admin)
- Employees: list with filters, create, update, soft-delete, restore (admin), force-delete (admin), bulk delete
- Authentication & RBAC: login success/failure, Users menu visibility per role, profile page access
- Activity Logs & Exports: logging on updates/deletes, CSV export headers for employees/departments
- Import Flow: CSV preview and process happy-path
- Security Headers: presence of key headers on authenticated pages

Assumptions:
- Simple RBAC via `role` column on users: admin, manager, viewer
- Auth middleware enabled for protected routes

## Prerequisites
- PHP and Composer installed
- Application dependencies installed (composer install)
- Test database configured in `.env` or `.env.testing`
- Migrations up to date

## Running Tests

Common commands:

- Run the full suite:
  - `php artisan test`
  - or `vendor/bin/phpunit`

- Run a specific test class:
  - `php artisan test tests/Feature/CrudUsersTest.php`

- Rebuild database from scratch before tests:
  - `php artisan migrate:fresh`

## Troubleshooting

- Unknown column errors (e.g., `role` missing): run migrations `php artisan migrate` or `php artisan migrate:fresh`
- Duplicate unique key on users.email: use `migrate:fresh` for a clean slate
- Missing factories: ensure User, Department, Employee factories exist in `database/factories`
- Authentication failures: ensure middleware and routes match tests

## Test File Overview

- `tests/Feature/CrudUsersTest.php`
  - Verifies RBAC protections; only admins can access Users pages and create users.
- `tests/Feature/CrudDepartmentsTest.php`
  - Covers full CRUD lifecycle including soft-deletes and admin-only restore/force-delete.
- `tests/Feature/CrudEmployeesTest.php`
  - Covers CRUD, filters, bulk delete, and trash flows.
- `tests/Feature/AuthAndRbacTest.php`
  - Login page loads, successful/failed authentication, role-based Users menu visibility, profile page availability.
- `tests/Feature/ActivityLogsAndExportsTest.php`
  - Activity log entries exist after updates; export endpoints return proper CSV headers.
- `tests/Feature/ImportFlowTest.php`
  - CSV import preview and process happy-path creates employees.
- `tests/Feature/SecurityHeadersTest.php`
  - Verifies security headers are present on authenticated responses.

## Expected Outcomes
- All CRUD flows succeed for allowed roles and fail with proper redirects for restricted roles.
- Database assertions confirm changes.
- Soft-deletes verified where applicable.
- Authentication flow validated with redirects and session errors.
- Activity logs recorded for key actions.
- CSV export endpoints respond with correct content headers.
- Import flow creates expected employees from valid CSV.
- Security headers applied across authenticated pages.

## Notes
- Feature tests use `RefreshDatabase` to isolate state per test.
- The `User` model hashes passwords automatically via casts. Tests validate this behavior.

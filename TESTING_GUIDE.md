# Testing Guide

This document explains the automated Feature tests for CRUD operations and how to run them.

## Scope

Feature tests cover:
- Users (admin-only): list, create (RBAC checks)
- Departments: list, create, show, edit, update, soft-delete, trash, restore (admin), force-delete (admin)
- Employees: list with filters, create, update, soft-delete, restore (admin), force-delete (admin), bulk delete

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

## Expected Outcomes
- All CRUD flows succeed for allowed roles and fail with proper redirects for restricted roles.
- Database assertions confirm changes.
- Soft-deletes verified where applicable.

## Notes
- Feature tests use `RefreshDatabase` to isolate state per test.
- The `User` model hashes passwords automatically via casts. Tests validate this behavior.

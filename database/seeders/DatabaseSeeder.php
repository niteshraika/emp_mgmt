<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user with a known strong password
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'P@ssw0rd',
            'role' => 'admin',
        ]);

        // Create test user with manager role
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'manager',
        ]);

        // Create viewer user
        User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'role' => 'viewer',
        ]);

        // Create 10 standard departments
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Manages employee relations and recruitment'],
            ['name' => 'Engineering', 'description' => 'Develops and maintains software products'],
            ['name' => 'Sales', 'description' => 'Drives revenue and customer acquisition'],
            ['name' => 'Marketing', 'description' => 'Brand strategy and customer engagement'],
            ['name' => 'Finance', 'description' => 'Manages budgets and financial planning'],
            ['name' => 'Operations', 'description' => 'Oversees daily business operations'],
            ['name' => 'Customer Support', 'description' => 'Provides customer assistance and support'],
            ['name' => 'Design', 'description' => 'Creates user experience and visual design'],
            ['name' => 'Product', 'description' => 'Product strategy and development'],
            ['name' => 'Legal', 'description' => 'Handles legal affairs and compliance'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create 100 dummy employees
        Employee::factory(100)->create();
    }
}

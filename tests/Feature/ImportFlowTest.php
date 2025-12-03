<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_import_preview_and_process(): void
    {
        $user = User::factory()->create(['role' => 'manager']);
        $dept = Department::factory()->create();

        Storage::fake('local');
        $csvContent = "first_name,last_name,email,phone,department_id,salary,joining_date,address\n".
            "John,Doe,john.doe@example.com,1234567890,{$dept->id},5000,2020-01-01,Street 1\n".
            "Jane,Roe,jane.roe@example.com,1234567899,{$dept->id},6000,2019-01-01,Street 2\n";
        $file = UploadedFile::fake()->createWithContent('employees.csv', $csvContent);

        // preview
        $this->actingAs($user)
            ->post('/employees/import-preview', ['csv_file' => $file])
            ->assertStatus(200)
            ->assertSee('preview');

        // process
        // simulate session having the temp path by reusing the same file path
        session(['import_file_path' => $file->getPathname()]);
        $this->actingAs($user)
            ->post('/employees/import-process')
            ->assertRedirect('/employees')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('employees', ['email' => 'john.doe@example.com']);
        $this->assertDatabaseHas('employees', ['email' => 'jane.roe@example.com']);
    }
}

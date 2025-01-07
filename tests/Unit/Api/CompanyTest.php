<?php

namespace Tests\Unit\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Company_Users;

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    protected string $token;
    protected $company;
    protected $employee;
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        // Set up your company, employee and manager here if needed
        $response = $this->post('/api/company_login', [
            'email' => 'alice.johnson@example.com',
            'password' => 'password'
        ]);
        
        $this->token = $response->json('access_token');
        $this->artisan('migrate');
    }
    
    public function test_database_tables_exist()
    {
        $this->assertModelExists(Company::class);
        $this->assertModelExists(Company_Users::class);
    }

    public function test_manager_can_login()
    {
        $response = $this->post('/api/company_login', [
            'email' => 'sam.lee@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['access_token']);
        
    }

    public function test_can_get_company_list()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get('/api/company/all');

        $response->assertStatus(200)
                ->assertJson([
                    'company' => true, // return page list laravel
                 ]);
    }

    public function test_can_get_company_by_id()
    {
        $company_id = 10;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get("/api/company/id/{$company_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'company' => ['id' => $company_id]
                ]);
    }

    public function test_can_get_employees_by_company_id()
    {
        $company_id = 10;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get("/api/company/id/{$company_id}/employee");

        $response->assertStatus(200)
            ->assertJson([
                'employees' => [
                    'data' => [
                        ['cid' => $company_id]
                    ]
                ]
            ]);
    }

    public function test_can_get_all_employees()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get('/api/company/employee');

        $response->assertStatus(200)
                ->assertJson([
                    'employee' => true, // return page list laravel
                 ]);
    }

    public function test_can_get_employee_by_id()
    {
        $employee_id = 10;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get("/api/company/employee/{$employee_id}");

        $response->assertStatus(200)
                ->assertJson([
                    'employee' => ['id' => $employee_id]
                ]);
    }

    public function test_manager_can_create_employee()
    {
        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->token
                        ])
                        ->post('/api/company/employee', [
                            'name' => 'Test Employee',
                            'email' => 'employee@test.com',
                            'phone_number' => '1234567890',
                            'address' => 'Test Address',
                            'password' => 'password',
                            'is_manager'=>0,

                        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('company_users', [
            'email' => 'employee@test.com'
        ]);
    }

    public function test_can_update_employee_by_id()
    {
        $employee_id = 10;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->post("/api/company/employee/{$employee_id}", [
            '_method' => 'PATCH',
            'name' => 'Updated Name',
            'email' => 'updated@email.com'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('company_users', [
            'id' => $employee_id,
            'name' => 'Updated Name',
            'email' => 'updated@email.com'
        ]);
    }

    public function test_can_delete_employee_by_id()
    {
        $employee_id = 10;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->delete("/api/company/employee/{$employee_id}");

        $response->assertStatus(200);
    }

    public function test_can_reactivate_deleted_employee()
    {
        $employee_id = 10;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->post("/api/company/employee/reactivate/{$employee_id}", [
            '_method' => 'PATCH'
        ]);

        $response->assertStatus(200);
    }
}
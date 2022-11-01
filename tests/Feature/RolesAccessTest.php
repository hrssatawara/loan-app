<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 *
 */
class RolesAccessTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_must_login_to_access_to_admin_dashboard()
    {
        $this->get(route('admin.test'))
            ->assertUnauthorized();
    }

    /** @test */
    public function test_admin_can_access_to_admin_dashboard()
    {
        //Having
        $adminUser = User::factory()->create();

        $adminUser->assignRole('Admin');

        Sanctum::actingAs($adminUser);

        //When
        $response = $this->get(route('admin.test'));

        //Then
        $response->assertOk();
    }

    /** @test */
    public function test_customer_can_not_access_to_admin_dashboard()
    {
        //Having
        $adminUser = User::factory()->create();

        $adminUser->assignRole('Customer');

        Sanctum::actingAs($adminUser);

        //When
        $response = $this->get(route('admin.test'));

        //Then
        $response->assertForbidden();
    }
}

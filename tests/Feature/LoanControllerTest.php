<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\ScheduleRepayment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 *
 */
class LoanControllerTest extends TestCase
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

    public function testCustomerStore()
    {
        //Having
        $customerUser = User::factory()->create();

        $customerUser->assignRole('Customer');

        Sanctum::actingAs($customerUser);

        //When
        $response = $this->post(route('customer.loan.store'), [
            'amount' => 3000,
            'tenure' => 5,
        ]);

        //Then
        $response->assertOk();

        $this->assertDatabaseHas('loans', [
            'amount' => 3000,
            'tenure' => 5,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCustomerStoreFailsValidation()
    {
        //Having
        $customerUser = User::factory()->create();

        $customerUser->assignRole('Customer');

        Sanctum::actingAs($customerUser);

        //When
        $response = $this->post(route('customer.loan.store'), [
            'amount' => 3000,
        ]);

        //Then
        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminStore()
    {
        //Having
        $adminUser = User::factory()->create();

        $adminUser->assignRole('Admin');

        Sanctum::actingAs($adminUser);

        //When
        $response = $this->post(route('customer.loan.store'), [
            'amount' => 3000,
            'tenure' => 5,
        ]);

        //Then
        $response->assertForbidden();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminShow()
    {
        //Having
        $adminUser = User::factory()->create();

        $adminUser->assignRole('Admin');

        Sanctum::actingAs($adminUser);

        $loan = Loan::factory()->create([
            'user_id' => $adminUser->id,
        ]);

        $scheduleRepayments = ScheduleRepayment::factory($loan->tenure)->create([
            'amount' => $loan->amount / $loan->tenure,
            'loan_id' => $loan->id,
        ]);


        //When
        $response = $this->get(route('admin.loan.show', [$loan->id]));

        //Then
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonPath('data.id', $loan->id);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCustomerShow()
    {
        //Having
        $customerUser = User::factory()->create();

        $customerUser->assignRole('Customer');

        Sanctum::actingAs($customerUser);

        $loan = Loan::factory()->create([
            'user_id' => $customerUser->id,
        ]);

        $scheduleRepayments = ScheduleRepayment::factory($loan->tenure)->create([
            'amount' => $loan->amount / $loan->tenure,
            'loan_id' => $loan->id,
        ]);


        //When
        $response = $this->get(route('customer.loan.show', [$loan->id]));

        //Then
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonPath('data.id', $loan->id);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminUpdate()
    {
        //Having
        $adminUser = User::factory()->create();

        $adminUser->assignRole('Admin');

        Sanctum::actingAs($adminUser);

        $loan = Loan::factory()->create([
            'user_id' => $adminUser->id,
        ]);

        //When
        $response = $this->put(route('admin.loan.update', [$loan->id]), [
            'status' => 200,
        ]);

        //Then
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonPath('data.status', 'APPROVED');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCustomerUpdate()
    {
        //Having
        $customerUser = User::factory()->create();

        $customerUser->assignRole('Customer');

        Sanctum::actingAs($customerUser);

        $loan = Loan::factory()->create([
            'user_id' => $customerUser->id,
        ]);

        //When
        $response = $this->put(route('admin.loan.update', [$loan->id]), [
            'status' => 200,
        ]);

        //Then
        $response->assertForbidden();
    }
}

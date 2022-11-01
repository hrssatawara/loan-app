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
class ScheduleRepaymentControllerTest extends TestCase
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
    public function testCustomerAddRepayment()
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
        $response = $this->put(route('customer.loan.add.repayment', [$loan->id]), [
            'amount' => 1000,
        ]);

        //Then
        $response->assertOk();
    }

    /**
     * @return void
     */
    public function testCustomerAddRepaymentFailsValidation()
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
        $response = $this->put(route('customer.loan.add.repayment', [$loan->id]), [
            'amounts' => 1000,
        ]);

        //Then
        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

}

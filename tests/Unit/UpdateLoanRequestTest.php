<?php

namespace Tests\Unit;

use App\Http\Requests\LoanRequest;

use App\Http\Requests\UpdateLoanRequest;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as IlluminateValidator;
use Tests\TestCase;

/**
 *
 */
class UpdateLoanRequestTest extends TestCase
{
    /**
     * @var IlluminateValidator
     */
    private IlluminateValidator $validator;

    /**
     * @var UpdateLoanRequest
     */
    protected UpdateLoanRequest $updateLoanRequest;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->updateLoanRequest = new UpdateLoanRequest();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRules()
    {
        $this->assertEquals([
            'status' => 'required|numeric|in:' . implode(',', array_keys(Loan::STATUS)),
        ],
            $this->updateLoanRequest->rules()
        );
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItPassesWithValidData()
    {
        $attributes = [
            'status' => 200,
        ];

        $validator = Validator::make($attributes, $this->updateLoanRequest->rules());

        $this->assertTrue($this->updateLoanRequest->authorize());
        $this->assertTrue($validator->passes());

    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItFailsWithMissingData()
    {

        $validator = Validator::make([], $this->updateLoanRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertCount(1, $validator->errors());
        $this->assertContains('status', $validator->errors()->keys());

    }

    /**
     * @return void
     */
    public function testItFailsWithInvalidData()
    {
        $attributes = [
            'status' => 400,
        ];

        $validator = Validator::make($attributes, $this->updateLoanRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertCount(1, $validator->errors());
        $this->assertContains('status', $validator->errors()->keys());

    }
}

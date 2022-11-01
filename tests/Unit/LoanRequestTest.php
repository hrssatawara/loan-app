<?php

namespace Tests\Unit;

use App\Http\Requests\LoanRequest;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as IlluminateValidator;
use Tests\TestCase;

/**
 *
 */
class LoanRequestTest extends TestCase
{
    /**
     * @var IlluminateValidator
     */
    private IlluminateValidator $validator;

    /**
     * @var LoanRequest
     */
    protected LoanRequest $loanRequest;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loanRequest = new LoanRequest();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRules()
    {
        $this->assertEquals([
            'amount' => 'required|numeric',
            'tenure' => 'required|integer',
        ],
            $this->loanRequest->rules()
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
            'amount' => 4000,
            'tenure' => 3,
        ];

        $validator = Validator::make($attributes, $this->loanRequest->rules());

        $this->assertTrue($this->loanRequest->authorize());
        $this->assertTrue($validator->passes());

    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItFailsWithMissingData()
    {
        $attributes = [
            'amount' => 4000,
        ];

        $validator = Validator::make($attributes, $this->loanRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertCount(1, $validator->errors());
        $this->assertContains('tenure', $validator->errors()->keys());

    }

    /**
     * @return void
     */
    public function testItFailsWithInvalidData()
    {
        $attributes = [
            'amount' => '4000s',
            'tenure' => '5s',
        ];


        $validator = Validator::make($attributes, $this->loanRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertCount(2, $validator->errors());
        $this->assertContains('amount', $validator->errors()->keys());
        $this->assertContains('tenure', $validator->errors()->keys());

    }
}

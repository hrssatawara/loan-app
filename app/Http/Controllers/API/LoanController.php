<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\LoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use App\Repository\LoanRepository;
use App\Repository\ScheduleRepaymentRepository;

/**
 *
 */
class LoanController extends BaseController
{
    /**
     * @var LoanRepository
     */
    protected LoanRepository $loanRepository;
    /**
     * @var ScheduleRepaymentRepository
     */
    protected ScheduleRepaymentRepository $scheduleRepaymentRepository;

    /**
     * @param LoanRepository $loanRepository
     * @param ScheduleRepaymentRepository $scheduleRepaymentRepository
     */
    public function __construct(LoanRepository $loanRepository, ScheduleRepaymentRepository $scheduleRepaymentRepository)
    {
        $this->loanRepository = $loanRepository;
        $this->scheduleRepaymentRepository = $scheduleRepaymentRepository;
        $this->authorizeResource(Loan::class, 'loan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     */
    public function store(LoanRequest $request)
    {
        $input = $request->all();
        $loan = $this->loanRepository->storeLoan($input);
        $this->scheduleRepaymentRepository->storeScheduleRepayment($input, $loan);

        return $this->sendResponse(new LoanResource($loan), 'Loan request submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        return $this->sendResponse(new LoanResource($loan), 'Loan information retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     */
    public function update(UpdateLoanRequest $request, Loan $loan)
    {
        $this->loanRepository->approveLoan($request->all(), $loan);
        return $this->sendResponse(new LoanResource($loan), 'Loan status updated successfully.');
    }

}

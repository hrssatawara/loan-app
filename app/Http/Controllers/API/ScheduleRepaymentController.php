<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\ScheduleRepayment;
use App\Repository\LoanRepository;
use App\Repository\ScheduleRepaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class ScheduleRepaymentController extends BaseController
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
     * @param ScheduleRepaymentRepository $scheduleRepaymentRepository
     * @param LoanRepository $loanRepository
     */
    public function __construct(ScheduleRepaymentRepository $scheduleRepaymentRepository, LoanRepository $loanRepository)
    {
        $this->scheduleRepaymentRepository = $scheduleRepaymentRepository;
        $this->loanRepository = $loanRepository;
    }

    /**
     * @param Request $request
     * @param $loan_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function addRepayment(Request $request, $loan_id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        return $this->scheduleRepaymentRepository->addRepayments($input, $loan_id, $this->loanRepository);

    }
}

<?php
namespace App\Repository;

use App\Models\Loan;
use App\Models\ScheduleRepayment;
use Illuminate\Support\Carbon;

class ScheduleRepaymentRepository
{
    protected Loan $loan;
    protected ScheduleRepayment $scheduleRepayment;

    public function __construct(ScheduleRepayment $scheduleRepayment, Loan $loan)
    {
        $this->scheduleRepayment = $scheduleRepayment;
        $this->loan = $loan;
    }

    public function storeScheduleRepayment(array $data, $loan): void
    {
        $total = 0;
        $repayment = (float)number_format($data['amount'] / $data['tenure'], 2, '.', '');

        $loan_id = $loan->id;
        $due_date = Carbon::now()->addDays(7)->format('Y-m-d');
        for ($i = 0; $i < $data['tenure']; $i++) {

            // Added logic to create amount like 33.33, 33.33, 33.34 for 100 amount

            if ($i === (int)$data['tenure'] - 1) {
                $repayment = $data['amount'] - $total;
            }
            $total = ($total + $repayment);

            $this->scheduleRepayment->create([
                'loan_id' => $loan_id,
                'amount' => $repayment,
                'due_date' => $due_date,
            ]);
            $due_date = Carbon::createFromFormat('Y-m-d', $due_date)->addDays(7)->format('Y-m-d');

        }
    }

    public function addRepayments($input, $loan_id, $loanRepository){
        $repayment = $this->scheduleRepayment->unPaid()->where('loan_id',$loan_id)->orderBy('id')->first();

        if ($repayment){
            if ($input['amount'] >= $repayment->amount){
                $repayment->amount_paid = $input['amount'];
                $repayment->paid_date = Carbon::now()->format('Y-m-d');
                $repayment->status = ScheduleRepayment::PAID;
                $repayment->save();
                $remaining_payments = ScheduleRepayment::unPaid()->where('loan_id',$loan_id)->orderBy('id')->count();
                if ($remaining_payments === 0){
                    $loanRepository->paidLoan($loan_id);
                }
                return response()->json(['success'=>true, 'message' => 'Loan repayment added successfully.']);
            }else{
                return response()->json(['success'=>false, 'message' => 'Please enter amount greater or equal to the schedule repayment ('.$repayment->amount.')'],400);
            }
        }else{
            return response()->json(['success'=>true, 'message' => 'No dues. It seems loan already paid.']);
        }

    }
}

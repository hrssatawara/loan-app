<?php
namespace App\Repository;

use App\Models\Loan;

class LoanRepository
{
    protected Loan $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function storeLoan(array $data)
    {
       $data['user_id'] = auth()->user()->id;

       return $this->loan->create($data);
    }

    public function approveLoan($data, $loan): void
    {
        $loan->status = $data['status'];
        $loan->save();
    }

    public function show($id)
    {
        return $this->loan->findOrFail($id);
    }

    public function paidLoan($loan_id): void
    {
        $loan = $this->show($loan_id);
        $loan->status = $this->loan::PAID;
        $loan->save();
    }
}

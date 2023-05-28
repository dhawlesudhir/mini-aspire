<?php

namespace App\Http\Controllers;

use App\Models\LoanAccount;
use App\Models\RepaymentScheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class RepaymentSchedulerController extends Controller
{
    public static function loansScheduler()
    {
        $loanAccounts = LoanAccount::where('status', '=', 2)->where('scheduled', '=', 0)->get();

        if (empty(count($loanAccounts))) {

            Log::info('RepaymentSchedulerController::loansScheduler => no pending application');
        } else {
            foreach ($loanAccounts as  $loanAccount) {
                RepaymentSchedulerController::scheduleRepayments($loanAccount);
            }
        }
    }

    public static function scheduleRepayments(LoanAccount $loanAccount)
    {
        $scheduleDetails = [];
        $amount = $loanAccount->bal_amount;
        $terms = $loanAccount->terms;

        $scheduleDetails['user_id'] = $loanAccount->user_id;
        $scheduleDetails['loan_account_id'] = $loanAccount->id;
        $scheduleDetails['nurration'] = 'auto scheduled';

        // $today = Carbon::today();
        $amountSum = 0;
        for ($term = 1; $term < $terms; $term++) {
            $termAmount = round($amount / $terms, 2);
            $scheduleDetails['term'] = $term;
            $scheduleDetails['amount'] = $termAmount;
            $scheduleDetails['due_date'] = Carbon::today()->addWeeks($term);
            $amountSum += $termAmount;
            RepaymentSchedulerController::store($scheduleDetails);
        }

        $scheduleDetails['term'] = $term;
        $scheduleDetails['amount'] = $amount - $amountSum;
        $scheduleDetails['due_date'] = Carbon::today()->addWeeks($term);
        $scheduledFlag = RepaymentSchedulerController::store($scheduleDetails);
        if ($scheduledFlag) {
            $loanAccount->scheduled = 1;
            $loanAccount->save();
        }
        // $loanAccount = LoanAccount::find($loanAccount->id);
    }

    public static function store(array $scheduleDetails)
    {
        return RepaymentScheduler::create($scheduleDetails);
    }


    /**
     * repayment processing for 3 conditions
     * 1) paidAmount < term due amount  : not allowed 
     * 2) paidAmount = upcoming term due amount : term status to PAID
     * 3) paidAmount > term due amount : update loan balance AND reschedule upcoming payments
     */
    public static function processRepayment($loanId, $paidAmount)
    {

        $due_payment =  LoanAccount::find($loanId)->pendingRepayments->first();

        if (!isset($due_payment->id)) {
            $responseCode = 404;
            $response = ['error' => 'loan due not found'];
        } else {
            $due_amt = $due_payment->amount;

            switch ($paidAmount) {

                case $paidAmount < $due_amt:
                    $responseCode = 406;
                    $response = ['error' => 'payment not allowed, amount less than due amount'];
                    break;

                case $paidAmount == $due_amt:
                    $due_payment->status = 2;
                    $due_payment->amount_paid = $paidAmount;
                    $due_payment->save();
                    LoanAccountController::loanPaymentUpdate($loanId, $paidAmount);
                    $responseCode = 200;
                    $response = ['msg' => 'amount paid'];
                    break;

                case $paidAmount > $due_amt:
                    $due_payment->status = 2;
                    $due_payment->amount_paid = $paidAmount;
                    $due_payment->save();
                    LoanAccountController::loanPaymentUpdate($loanId, $paidAmount);
                    RepaymentSchedulerController::resheduleRepayments($loanId);
                    $responseCode = 200;
                    $response = ['msg' => 'amount paid & loan schedule updated'];
                    break;

                default:
                    $responseCode = 304;
                    $response = ['error' => 'unknown error occured'];
                    break;
            }
        }

        return response($response, $responseCode);
    }


    /**
     * reSchedulling/updating pending repayments If 
     * 1) If LoamAccount status PAID : update all pending repayments status to  PAID
     * 2) extra amount received for term : get adjusted in next repayments
     */
    public static function resheduleRepayments($loanId)
    {
        $loanAccount = LoanAccount::find($loanId);
        $loanBalance = $loanAccount->bal_amount;
        $loanStatus = $loanAccount->status;

        $scheduledRepayments = $loanAccount->pendingRepayments;
        if ($loanStatus == "PAID") {
            foreach ($scheduledRepayments as $schedule) {
                $schedule->status = 2;
                $schedule->nurration = "mark as PAID because Loan status PAID";
                $schedule->save();
            }
        } else {
            $remaningTerms = count($scheduledRepayments);
            foreach ($scheduledRepayments as $schedule) {
                $schedule->amount = round($loanBalance / $remaningTerms, 2);
                $schedule->nurration = "amount updated";
                $schedule->save();
            }
        }
    }
}

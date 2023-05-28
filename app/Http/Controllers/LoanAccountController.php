<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoanAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class LoanAccountController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user->type == "ADMIN") {
            $loanAccounts = LoanAccount::all();
        } else {
            $loanAccounts = LoanAccount::where('user_id', '=', $user->id)->get();
        }

        return response(['data' => $loanAccounts], 200);
    }

    public function submitLoan(Request $request)
    {

        $form = $request->validate([
            'amount' => 'required|numeric',
            'terms' => 'required|numeric',
            'purpose' => 'string|null',
        ]);

        if ($request->user('sanctum') != null) {
            $userId = $request->user('sanctum')->id;
            $form['user_id'] = $userId;
        } else {
            $userId = AuthController::registerCustomer($request);
            if (isset($userId)) {
                $form['user_id'] = $userId;
            } else {
                return response(['error' => 'try again!'], 409);
            }
        }
        return $this->saveLoan($form);
    }

    public function saveLoan(array $form)
    {

        if (!isset($form['purpose'])) {
            $form['purpose'] = null;
        }
        $form['status'] = 1;

        $loanAccount = LoanAccount::create($form);

        $user = User::find($loanAccount->user_id);
        $loanAccount['name'] = $user->first_name . " " . $user->last_name;

        unset($loanAccount['bal_amount']);

        $response = ['msg' => 'success', 'account' => $loanAccount];

        return response($response, 201);
    }

    public function show($loanId)
    {

        $loanAccount = LoanAccount::find($loanId);
        Gate::authorize('view', $loanAccount);

        if (!isset($loanAccount)) {
            return response(['error' => 'not found'], 204);
        }

        $loanAccount->pendingRepayments;

        return response(['details' => $loanAccount], 302);
    }

    public function approveLoan($loanId)
    {
        Gate::allowIf(fn (User $user) => $user->type == "ADMIN");

        $loanAccount = LoanAccount::find($loanId);

        if (!isset($loanAccount)) {
            $responseCode = 404;
            $response = ['error' => 'loan account not found'];
        } else {
            if ($loanAccount->status == "PENDING") {
                $loanAccount->status = 2;
                $loanAccount->bal_amount = $loanAccount->amount;
                $loanAccount->approved_by = Auth::id();
                $loanAccount->approved_on = Carbon::now();
                $saved = $loanAccount->save();
                if ($saved) {
                    $responseCode = 202;
                    $response = ['loan' => $loanAccount];
                } else {
                    $responseCode = 304;
                    $response = ['error' => 'try again'];
                }
            } else {
                $responseCode = 400;
                $response = ['error' => 'loan does not require approval'];
            }
        }

        return response($response, $responseCode);
    }

    public function getLoanRepaymentSchedule($loanId)
    {
        $loanAccount = LoanAccount::find($loanId);
        Gate::authorize('view', $loanAccount);
        return $loanAccount->repayments;
    }

    public function loanPayment(Request $request)
    {


        $form = $request->validate([
            'id' => 'required|numeric|exists:loan_accounts',
            'amount' => 'required|numeric',
        ]);

        $loanId = $form['id'];
        $paidAmount = round($form['amount'], 2);

        Gate::authorize('view', LoanAccount::find($loanId));

        return RepaymentSchedulerController::processRepayment($loanId, $paidAmount);
    }

    /**
     * update loan balance amount and condition based status
     * 1) paidAmount < balance amount : update balance amount
     * 1) paidAmount > balance amount : update balance & change status to PAID
     */
    public static function loanPaymentUpdate($loanId, $paidAmount)
    {
        $loanAccount = LoanAccount::find($loanId);
        $balance =  $loanAccount->bal_amount;
        if ($paidAmount < $balance) {
            $loanAccount->bal_amount = $balance - $paidAmount;
        } else {
            $loanAccount->bal_amount = $balance - $paidAmount;
            $loanAccount->status = 3;
        }

        $loanAccount->save();
    }
}

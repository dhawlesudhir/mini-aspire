<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoanAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Arrays;


class LoanAccountController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user->type == 1) {
            $loanAccounts = LoanAccount::all();
        } else {
            $loanAccounts = LoanAccount::where('user_id', '=', $user->id)->get();
        }

        return response(['data' => $loanAccounts], 200);
    }

    public function store(Request $request)
    {

        $form = $request->validate([
            'amount' => 'required|numeric',
            'terms' => 'required|numeric',
            'purpose' => 'string|null',
        ]);

        if ($request->user('sanctum') != null) {
            $user_id = $request->user('sanctum')->id;
            $form['user_id'] = $user_id;
        } else {
            $user_id = AuthController::registerCustomer($request);
            if (isset($user_id)) {
                $form['user_id'] = $user_id;
            } else {
                return response(['error' => 'try again!'], 409);
            }
        }
        return $this->submitloan($form);
    }

    public function submitloan(array $form)
    {

        if (!isset($form['purpose'])) {
            $form['purpose'] = null;
        }

        $loanaccount = LoanAccount::create([
            'user_id' => $form['user_id'],
            'amount' => $form['amount'],
            'bal_amount' => $form['amount'],
            'terms' => $form['terms'],
            'purpose' => $form['purpose'] ? $form['purpose'] : '',
            'status' => 1,
        ]);

        $user = User::find($loanaccount->user_id);
        $loanaccount['name'] = $user->first_name . " " . $user->last_name;

        unset($loanaccount['bal_amount']);

        $response = ['msg' => 'success', 'account' => $loanaccount];

        return response($response, 201);
    }

    public function show($loanid)
    {

        $loanAccount = LoanAccount::find($loanid);
        if (!isset($loanAccount)) {
            return response(['error' => 'not found'], 204);
        }

        return response(['details' => $loanAccount], 302);
    }

    public function update($loanid)
    {
        $loanAccount = LoanAccount::find($loanid);
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
}

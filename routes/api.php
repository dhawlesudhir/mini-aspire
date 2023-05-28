<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanAccountController;
use App\Http\Controllers\RepaymentSchedulerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


/**
 * below routes access only for login users
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/loan/{loanid}', [LoanAccountController::class, 'show']);
    Route::get('/loans', [LoanAccountController::class, 'index']);
    Route::patch('/loan/approve/{loanid}', [LoanAccountController::class, 'approveLoan']);
    Route::get('/loan/schedule/{loanid}', [LoanAccountController::class, 'getLoanRepaymentSchedule']);
    Route::post('/loan/payment', [LoanAccountController::class, 'loanPayment']);
});

/**
 * below routes doen't need login
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/loan/submit', [LoanAccountController::class, 'submitLoan']);

// Route::patch('/shedulerepayments', [RepaymentSchedulerController::class, 'loansScheduler']);

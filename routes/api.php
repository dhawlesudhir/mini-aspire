<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanAccountController;
use App\Models\LoanAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/loan', [LoanAccountController::class, 'store']);
// Route::get('/loan', [LoanAccountController::class, 'show'])->middleware('auth:santum');
Route::get('/loan/{loanid}', [LoanAccountController::class, 'show']);
Route::get('/loans', [LoanAccountController::class, 'index'])->middleware('auth:sanctum');
Route::patch('/approve/loan/{loanid}', [LoanAccountController::class, 'update'])->middleware('auth:sanctum');

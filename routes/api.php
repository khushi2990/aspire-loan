<?php

use App\Http\Controllers\Api\v1\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\PassportAuthController;

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
//Auth apis
Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
  
Route::middleware('auth:api')->group(function () {
    Route::get('get-user-profile', [PassportAuthController::class, 'userInfo']);
    Route::post('e-kyc-verify',[PassportAuthController::class, 'kycverification']);

    // Loan apis
    Route::post('/loan-request', [LoanController::class, 'submitLoanRequest']); 
    Route::post('/loan-approve-request', [LoanController::class, 'approveLoanRequest']); 
    Route::post('/loan-intallment-pay', [LoanController::class, 'payLoan']);	

    Route::get('/loan-intallments/{requestId}', [LoanController::class, 'getInstallmentDetails']);	
    
});
<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\ScheduleRepaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    return 'Welcome Customer!';
})->name('customer.test');

Route::name('customer.loan.')->group(function (){
    Route::post('loan',[LoanController::class,'store'])->name('store');
    Route::get('loan/{loan}',[LoanController::class,'show'])->name('show');
    Route::put('add/repayment/{loan}',[ScheduleRepaymentController::class,'addRepayment'])->name('add.repayment');
});

<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LoanController;
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
Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
});

Route::name('admin.loan.')->group(function (){
    Route::post('loan',[LoanController::class,'store'])->name('store');
    Route::get('loan/{loan}',[LoanController::class,'show'])->name('show');
    Route::put('loan/{loan}',[LoanController::class,'update'])->name('update');
});

Route::get('/test', function () {
    return 'Welcome Admin!';
})->name('admin.test');

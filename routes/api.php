<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CacheController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PostController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('cache')->group(function () {
    Route::post('purge', [CacheController::class, 'purge']);
});

Route::get('new-load-more', [PostController::class, 'loadMore'])->name('new-load-more');

Route::get('/on-change-term', [CustomerController::class, 'onChangeTerm'])->name('on-change-term');
Route::get('/get-rate', [CustomerController::class, 'getRates'])->name('get-rate');
Route::post('/update-bank-account', [CustomerController::class, 'updateBankAccount'])->name('update-bank-account');
Route::post('/verify-otp', [CustomerController::class, 'verifyOTP'])->name('verify-otp');



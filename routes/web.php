<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\UpdatePasswordController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\DynamicRouteController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Account\AccountController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dang-nhap',  [AuthenticatedSessionController::class, 'create'])->name('login.show');
Route::post('/login',  [AuthenticatedSessionController::class, 'login'])->name('login.store');
Route::get('/dang-ky',  [RegisterController::class, 'create'])->name('register.show');
Route::group(['middleware' => 'auth'], function () {
    Route::post('/loan-confirm', [CustomerController::class, 'storeLoanConfirm'])->name('loan_confirm.store');
    Route::get('/xac-nhan-khoan-vay', [CustomerController::class, 'showLoanConfirm'])->name('loan_confirm.show');
    Route::post('/account-validateion', [CustomerController::class, 'storeAccountVerification'])->name('account_verification.store');
    Route::get('/xac-minh-tai-khoan', [CustomerController::class, 'showAccountVerification'])->name('account_verification.show');
    Route::get('/thong-tin-ca-nhan', [CustomerController::class, 'showPersonalInfo'])->name('personal_info.show');
    Route::post('/personal-info', [CustomerController::class, 'storePersonalInfo'])->name('personal_info.store');
    Route::get('/thong-tin-ngan-hang', [CustomerController::class, 'showBankInfo'])->name('bank_info.show');
    Route::post('/bank-info', [CustomerController::class, 'storeBankInfo'])->name('bank_info.store');
    Route::get('/xac-minh-thong-tin', [CustomerController::class, 'showVerifyInfo'])->name('verify_info.show');
    Route::get('/xac-minh-vay', [CustomerController::class, 'showSignatureConfirm'])->name('signature_confirm.show');
    Route::post('/signature-confirm', [CustomerController::class, 'storeSignatureConfirm'])->name('signature_confirm.store');
    Route::get('/vay-thanh-cong', [CustomerController::class, 'showSuccessLoan'])->name('success_loan.show');
    Route::get('/thong-tin-tai-khoan', [AccountController::class, 'showAccountInformation'])->name('account_information.show');
    Route::get('/hop-dong-vay', [AccountController::class, 'showLoanContract'])->name('loan_contract.show');
    Route::get('/hop-dong-vay-pdf', [AccountController::class, 'showLoanContractPDF'])->name('loan_contract_pdf.show');
    Route::get('/doi-mat-khau', [UpdatePasswordController::class, 'show'])->name('update_password.show');
    Route::post('/doi-mat-khau', [UpdatePasswordController::class, 'store'])->name('update_password.update');
    Route::get('/bien-dong-tai-khoan', [AccountController::class, 'showAccountVolatility'])->name('account_volatility.show');
    Route::get('/lien-ket-ngan-hang', [AccountController::class, 'showBankLink'])->name('bank_link.show');
    Route::post('/lien-ket-ngan-hang', [AccountController::class, 'updateBankLink'])->name('bank_link.update');
    Route::get('/vi-tien', [AccountController::class, 'showWallet'])->name('wallet.show');
    Route::get('/danh-sach-rut-tien', [AccountController::class, 'showWithdrawals'])->name('withdrawals.show');
    Route::post('/danh-sach-rut-tien', [AccountController::class, 'storeWithdrawals'])->name('withdrawals.store');
    Route::get('/ho-so', [AccountController::class, 'showBrief'])->name('brief.show');
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});
Route::get('/thong-bao', [PageController::class, 'showNotification'])->name('notification');
Route::get('/duyet-vay', [PageController::class, 'showBrowseLoan'])->name('browse_loan');
Route::get('/lien-he', [PageController::class, 'showContact'])->name('contact');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/tin-tuc', [PostController::class, 'index'])->name('news');
Route::get('/tin-tuc/{slug}', [PostController::class, 'detail'])->name(config('constant.route_news_detail'));
Route::get('/{slug}', [DynamicRouteController::class, 'handle'])->name('common.slug');

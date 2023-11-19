<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth:api')->get('/logout', [AuthController::class, 'logout']);
});

// Admin-only routes
Route::middleware(['auth:api', 'admin'])->prefix('transactions')->group(function () {
    Route::post('/create-transaction', [TransactionController::class, 'createTransaction']);
    Route::post('/record-payment', [TransactionController::class, 'recordPayment']);
    Route::post('/generate-monthly-report', [TransactionController::class, 'generateMonthlyReport']);
});

// User routes
Route::middleware('auth:api')->prefix('transactions')->group(function () {
    Route::get('/view-transactions', [TransactionController::class, 'viewTransactions']);
});

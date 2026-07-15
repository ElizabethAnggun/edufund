<?php

use App\Http\Controllers\Admin\CampaignManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolVerificationController;
use App\Http\Controllers\Admin\TransactionMonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// School Verification
Route::get('/schools', [SchoolVerificationController::class, 'index'])->name('schools.index');
Route::get('/schools/{school}', [SchoolVerificationController::class, 'show'])->name('schools.show');
Route::post('/schools/{school}/verify', [SchoolVerificationController::class, 'verify'])->name('schools.verify');
Route::post('/schools/{school}/reject', [SchoolVerificationController::class, 'reject'])->name('schools.reject');

// Campaign Management
Route::get('/campaigns', [CampaignManagementController::class, 'index'])->name('campaigns.index');
Route::post('/campaigns/{campaign}/toggle-status', [CampaignManagementController::class, 'toggleStatus'])->name('campaigns.toggle-status');

// Transaction Monitoring
Route::get('/transactions', [TransactionMonitoringController::class, 'index'])->name('transactions.index');
Route::get('/transactions/{transaction}', [TransactionMonitoringController::class, 'show'])->name('transactions.show');
Route::post('/transactions/{transaction}/retry', [TransactionMonitoringController::class, 'retry'])->name('transactions.retry');

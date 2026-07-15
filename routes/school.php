<?php

use App\Http\Controllers\School\DashboardController;
use App\Http\Controllers\School\FundingRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:school'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware(['school.profile'])->group(function () {
        // Funding Requests
        Route::get('/funding-requests', [FundingRequestController::class, 'index'])->name('funding-requests.index');
        Route::get('/funding-requests/{fundingRequest}', [FundingRequestController::class, 'show'])->name('funding-requests.show');
        Route::post('/funding-requests/{fundingRequest}/approve', [FundingRequestController::class, 'approve'])->name('funding-requests.approve');
        Route::post('/funding-requests/{fundingRequest}/reject', [FundingRequestController::class, 'reject'])->name('funding-requests.reject');
        
        // Student List
        Route::get('/students', [DashboardController::class, 'students'])->name('students.index');
        
        // Verification History
        Route::get('/verifications', [DashboardController::class, 'verifications'])->name('verifications.index');
    });
});
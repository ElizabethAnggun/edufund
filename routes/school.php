<?php

use App\Http\Controllers\School\DashboardController;
use App\Http\Controllers\School\DisbursementController;
use App\Http\Controllers\School\FundingRequestController;
use App\Http\Controllers\School\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:school'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // School Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    
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

        // Disbursements (school -> student fund releases)
        Route::get('/disbursements', [DisbursementController::class, 'index'])->name('disbursements.index');
        Route::get('/funding-requests/{fundingRequest}/disbursements/create', [DisbursementController::class, 'create'])->name('disbursements.create');
        Route::post('/funding-requests/{fundingRequest}/milestones/{milestone}/disburse', [DisbursementController::class, 'store'])->name('disbursements.store');
        Route::get('/disbursements/{disbursement}', [DisbursementController::class, 'show'])->name('disbursements.show');
    });
});
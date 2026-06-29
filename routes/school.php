<?php

use App\Http\Controllers\School\DashboardController;
use App\Http\Controllers\School\FundingRequestController;
use App\Http\Middleware\EnsureSchoolProfileExists;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(EnsureSchoolProfileExists::class)->group(function () {
    Route::get('/funding-requests', [FundingRequestController::class, 'index'])->name('funding-requests.index');
    Route::get('/funding-requests/{fundingRequest}', [FundingRequestController::class, 'show'])->name('funding-requests.show');
    Route::post('/funding-requests/{fundingRequest}/approve', [FundingRequestController::class, 'approve'])->name('funding-requests.approve');
    Route::post('/funding-requests/{fundingRequest}/reject', [FundingRequestController::class, 'reject'])->name('funding-requests.reject');
});

<?php

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\FundingRequestController;
use App\Http\Controllers\Student\MilestoneController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    
    // Funding Requests
    Route::get('/funding-requests', [FundingRequestController::class, 'index'])->name('funding-requests.index');
    Route::get('/funding-requests/create', [FundingRequestController::class, 'create'])->name('funding-requests.create');
    Route::post('/funding-requests', [FundingRequestController::class, 'store'])->name('funding-requests.store');
    Route::get('/funding-requests/{fundingRequest}', [FundingRequestController::class, 'show'])->name('funding-requests.show');
    Route::get('/funding-requests/{fundingRequest}/edit', [FundingRequestController::class, 'edit'])->name('funding-requests.edit');
    Route::put('/funding-requests/{fundingRequest}', [FundingRequestController::class, 'update'])->name('funding-requests.update');
    Route::delete('/funding-requests/{fundingRequest}', [FundingRequestController::class, 'destroy'])->name('funding-requests.destroy');
    
    // Milestones
    Route::get('/milestones', [MilestoneController::class, 'index'])->name('milestones.index');
    Route::get('/milestones/{milestone}', [MilestoneController::class, 'show'])->name('milestones.show');
    Route::post('/milestones/{milestone}/submit', [MilestoneController::class, 'submit'])->name('milestones.submit');
    
    // Supporting Documents
    Route::get('/documents', [FundingRequestController::class, 'documents'])->name('documents.index');
    Route::post('/documents/upload', [FundingRequestController::class, 'uploadDocument'])->name('documents.upload');
    
    // Achievements
    Route::get('/achievements', [DashboardController::class, 'achievements'])->name('achievements.index');
    
    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications.index');
    
    // Settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});


<?php

use App\Http\Controllers\Donor\DashboardController;
use App\Http\Controllers\Donor\CampaignController;
use App\Http\Controllers\Donor\DonationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:donor'])->prefix('donor')->name('donor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Browse Campaigns
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    
    // Donation History
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
    
    // Saved Campaigns
    Route::get('/saved-campaigns', [CampaignController::class, 'saved'])->name('saved-campaigns.index');
    Route::post('/campaigns/{campaign}/save', [CampaignController::class, 'save'])->name('campaigns.save');
    Route::delete('/campaigns/{campaign}/unsave', [CampaignController::class, 'unsave'])->name('campaigns.unsave');
    
    // Wallet (placeholder for Stellar integration)
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');
    
    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications.index');
    
    // Settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});
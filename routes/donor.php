<?php

use App\Http\Controllers\Donor\CampaignController;
use App\Http\Controllers\Donor\DashboardController;
use App\Http\Controllers\Donor\DonationController;
use App\Http\Controllers\Donor\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:donor'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Browse Campaigns
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');

    // Donation Checkout
    Route::get('/campaigns/{campaign}/donate', [CampaignController::class, 'donate'])->name('campaigns.donate');
    Route::post('/campaigns/{campaign}/donate/confirm', [CampaignController::class, 'confirmDonation'])->name('campaigns.donate.confirm');

    // Donation History
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');

    // Saved Campaigns
    Route::get('/saved-campaigns', [CampaignController::class, 'saved'])->name('saved-campaigns.index');
    Route::post('/campaigns/{campaign}/save', [CampaignController::class, 'save'])->name('campaigns.save');
    Route::delete('/campaigns/{campaign}/unsave', [CampaignController::class, 'unsave'])->name('campaigns.unsave');

    // Wallet
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');
    Route::post('/wallet/bind', [WalletController::class, 'bind'])->name('wallet.bind');
    Route::get('/wallet/balance', [WalletController::class, 'getBalance'])->name('wallet.balance');

    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications.index');

    // Settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});

<?php

use App\Http\Controllers\Donor\CampaignController;
use App\Http\Controllers\Donor\DashboardController;
use App\Http\Controllers\Donor\DonationController;
use App\Http\Controllers\Donor\SavedCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');
Route::get('/saved-campaigns', [SavedCampaignController::class, 'index'])->name('saved-campaigns');

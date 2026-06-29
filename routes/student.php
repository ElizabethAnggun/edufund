<?php

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\FundingRequestController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Middleware\EnsureStudentProfileExists;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile', [ProfileController::class, 'store'])->name('profile');

Route::middleware(EnsureStudentProfileExists::class)->group(function () {
    Route::get('/funding', [FundingRequestController::class, 'index'])->name('funding-requests.index');
    Route::get('/funding/create', [FundingRequestController::class, 'create'])->name('funding-requests.create');
    Route::post('/funding', [FundingRequestController::class, 'store'])->name('funding-requests.store');
    Route::get('/funding/{fundingRequest}', [FundingRequestController::class, 'show'])->name('funding-requests.show');
    Route::get('/funding/{fundingRequest}/edit', [FundingRequestController::class, 'edit'])->name('funding-requests.edit');
    Route::put('/funding/{fundingRequest}', [FundingRequestController::class, 'update'])->name('funding-requests.update');
    Route::delete('/funding/{fundingRequest}', [FundingRequestController::class, 'destroy'])->name('funding-requests.destroy');
    Route::post('/funding/{fundingRequest}/submit', [FundingRequestController::class, 'submit'])->name('funding-requests.submit');
    Route::post('/funding/{fundingRequest}/documents', [FundingRequestController::class, 'uploadDocument'])->name('funding-requests.documents.upload');
    Route::delete('/funding/{fundingRequest}/documents/{document}', [FundingRequestController::class, 'deleteDocument'])->name('funding-requests.documents.delete');
});

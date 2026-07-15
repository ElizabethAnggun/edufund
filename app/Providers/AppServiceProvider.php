<?php

namespace App\Providers;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\FundingRequestServiceInterface;
use App\Contracts\Services\StudentDashboardServiceInterface;
use App\Contracts\Services\StudentProfileServiceInterface;
use App\Contracts\Services\SupportingDocumentServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Services\AuthService;
use App\Services\FundingRequestService;
use App\Services\StudentDashboardService;
use App\Services\StudentProfileService;
use App\Services\SupportingDocumentService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(StudentProfileServiceInterface::class, StudentProfileService::class);
        $this->app->bind(StudentDashboardServiceInterface::class, StudentDashboardService::class);
        $this->app->bind(FundingRequestServiceInterface::class, FundingRequestService::class);
        $this->app->bind(SupportingDocumentServiceInterface::class, SupportingDocumentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Contracts\Services\AdminDashboardServiceInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\DonationServiceInterface;
use App\Contracts\Services\DonorDashboardServiceInterface;
use App\Contracts\Services\FundingRequestServiceInterface;
use App\Contracts\Services\MilestoneServiceInterface;
use App\Contracts\Services\SchoolDashboardServiceInterface;
use App\Contracts\Services\SchoolProfileServiceInterface;
use App\Contracts\Services\StellarServiceInterface;
use App\Contracts\Services\StudentDashboardServiceInterface;
use App\Contracts\Services\StudentProfileServiceInterface;
use App\Contracts\Services\SupportingDocumentServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Services\AdminDashboardService;
use App\Services\AuthService;
use App\Services\DonationService;
use App\Services\DonorDashboardService;
use App\Services\FundingRequestService;
use App\Services\MilestoneService;
use App\Services\SchoolDashboardService;
use App\Services\SchoolProfileService;
use App\Services\StellarService;
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
        $this->app->bind(DonorDashboardServiceInterface::class, DonorDashboardService::class);
        $this->app->bind(SchoolDashboardServiceInterface::class, SchoolDashboardService::class);
        $this->app->bind(SchoolProfileServiceInterface::class, SchoolProfileService::class);
        $this->app->bind(MilestoneServiceInterface::class, MilestoneService::class);
        $this->app->bind(StellarServiceInterface::class, StellarService::class);
        $this->app->bind(AdminDashboardServiceInterface::class, AdminDashboardService::class);
        $this->app->bind(DonationServiceInterface::class, DonationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

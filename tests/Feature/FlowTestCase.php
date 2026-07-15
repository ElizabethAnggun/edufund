<?php

namespace Tests\Feature;

use App\Contracts\Services\StellarServiceInterface;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Stubs\FakeStellarService;
use Tests\TestCase;

/**
 * Base test case for the core EduFund business flows.
 *
 * - Refreshes the database between tests.
 * - Seeds the permission & role tables (required by the Spatie role middleware).
 * - Swaps the real Stellar service for an in-memory fake so no network calls
 *   are made during tests.
 */
abstract class FlowTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        // Reset the fake's configurable state before each test.
        FakeStellarService::$verifyTransactionResult = true;

        app()->bind(StellarServiceInterface::class, FakeStellarService::class);
    }
}

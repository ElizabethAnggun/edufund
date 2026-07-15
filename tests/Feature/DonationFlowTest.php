<?php

namespace Tests\Feature;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Enums\DonationStatus;
use App\Enums\UserRole;
use App\Models\Campaign;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Stubs\FakeStellarService;

class DonationFlowTest extends FlowTestCase
{
    private function createDonor(): User
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
            'wallet_address' => 'GDONORTESTWALLETADDRESS',
        ]);
        $user->assignRole(UserRole::DONOR->value);

        return $user;
    }

    private function createCampaign(): Campaign
    {
        $schoolUser = User::factory()->create(['password' => Hash::make('password')]);
        $schoolUser->assignRole(UserRole::SCHOOL->value);
        $school = School::create([
            'user_id' => $schoolUser->id,
            'name' => 'Donation School',
            'stellar_wallet_address' => 'GSCHOOLTESTWALLET',
            'verification_status' => 'verified',
        ]);

        return Campaign::create([
            'school_id' => $school->id,
            'title' => 'Kampanye Test',
            'slug' => 'kampanye-test',
            'description' => 'Deskripsi kampanye.',
            'goal_amount' => 10000000,
            'current_amount' => 0,
            'status' => CampaignStatus::ACTIVE,
            'visibility' => CampaignVisibility::PUBLIC,
            'published_at' => now(),
        ]);
    }

    public function test_donor_can_browse_campaigns(): void
    {
        $campaign = $this->createCampaign();
        $donor = $this->createDonor();

        $this->actingAs($donor)
            ->get(route('donor.campaigns.index'))
            ->assertOk()
            ->assertSee($campaign->title);
    }

    public function test_donor_can_view_campaign(): void
    {
        $campaign = $this->createCampaign();
        $donor = $this->createDonor();

        $this->actingAs($donor)
            ->get(route('donor.campaigns.show', $campaign))
            ->assertOk()
            ->assertSee($campaign->title);
    }

    public function test_donor_can_complete_donation(): void
    {
        $campaign = $this->createCampaign();
        $donor = $this->createDonor();

        $response = $this->actingAs($donor)->post(route('donor.campaigns.donate.confirm', $campaign), [
            'tx_hash' => 'TXDONATETEST123',
            'from_address' => 'GDONORTESTWALLETADDRESS',
            'amount' => 1000000,
            'message' => 'Semangat belajar!',
            'anonymous' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('donations', [
            'campaign_id' => $campaign->id,
            'status' => DonationStatus::COMPLETED->value,
        ]);
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'current_amount' => 1000000,
        ]);
    }

    public function test_donor_donation_fails_when_transaction_unverified(): void
    {
        FakeStellarService::$verifyTransactionResult = false;

        $campaign = $this->createCampaign();
        $donor = $this->createDonor();

        $this->actingAs($donor)->post(route('donor.campaigns.donate.confirm', $campaign), [
            'tx_hash' => 'TXDONATEFAIL123',
            'from_address' => 'GDONORTESTWALLETADDRESS',
            'amount' => 1000000,
        ]);

        $this->assertDatabaseHas('donations', [
            'campaign_id' => $campaign->id,
            'status' => DonationStatus::FAILED->value,
        ]);
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'current_amount' => 0,
        ]);
    }
}

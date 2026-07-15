<?php

namespace App\Http\Controllers\Donor;

use App\Contracts\Services\DonationServiceInterface;
use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::where('status', CampaignStatus::ACTIVE)
            ->where('visibility', CampaignVisibility::PUBLIC)
            ->with(['school', 'fundingRequest.studentProfile.user'])
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('donor.campaigns.index', [
            'campaigns' => $campaigns
        ]);
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load([
            'school',
            'fundingRequest.studentProfile.user',
            'milestones',
            'donations' => function ($q) {
                $q->where('status', 'completed')->latest()->limit(10);
            },
        ]);

        $isSaved = auth()->user()->savedCampaigns()->where('campaign_id', $campaign->id)->exists();

        return view('donor.campaigns.show', [
            'campaign' => $campaign,
            'isSaved' => $isSaved,
        ]);
    }

    public function donate(Campaign $campaign): View
    {
        $campaign->load(['school', 'fundingRequest.studentProfile.user']);

        $walletAddress = auth()->user()->wallet_address;

        return view('donor.campaigns.donate', [
            'campaign' => $campaign,
            'walletAddress' => $walletAddress,
        ]);
    }

    public function confirmDonation(Request $request, Campaign $campaign, DonationServiceInterface $donationService): RedirectResponse
    {
        $request->validate([
            'tx_hash' => 'required|string|max:100',
            'from_address' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.1',
            'message' => 'nullable|string|max:500',
            'anonymous' => 'sometimes|boolean',
        ]);

        $donor = auth()->user();

        $donation = $donationService->createDonation($donor, $campaign, [
            'amount' => $request->amount,
            'currency' => 'XLM',
            'message' => $request->message,
            'anonymous' => $request->boolean('anonymous', false),
        ]);

        $confirmed = $donationService->confirmDonation(
            $donation,
            $request->tx_hash,
            $request->from_address
        );

        if ($confirmed) {
            return redirect()->route('donor.donations.show', $donation)
                ->with('success', 'Donation completed successfully! Transaction verified on Stellar network.');
        }

        return redirect()->route('donor.donations.show', $donation)
            ->with('error', 'Donation recorded but transaction verification is pending. Please check back later.');
    }

    public function saved(): View
    {
        $campaigns = auth()->user()->savedCampaigns()
            ->with(['school', 'fundingRequest.studentProfile.user'])
            ->get();

        return view('donor.saved-campaigns', [
            'campaigns' => $campaigns
        ]);
    }

    public function save(Campaign $campaign): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->savedCampaigns()->where('campaign_id', $campaign->id)->exists()) {
            $user->savedCampaigns()->attach($campaign->id);
        }

        return back()->with('success', 'Campaign saved to your list.');
    }

    public function unsave(Campaign $campaign): RedirectResponse
    {
        auth()->user()->savedCampaigns()->detach($campaign->id);

        return back()->with('success', 'Campaign removed from your list.');
    }
}

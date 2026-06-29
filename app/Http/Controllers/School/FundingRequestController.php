<?php

namespace App\Http\Controllers\School;

use App\Contracts\Services\FundingRequestServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveFundingRequestRequest;
use App\Http\Requests\RejectFundingRequestRequest;
use App\Models\FundingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FundingRequestController extends Controller
{
    public function __construct(
        private readonly FundingRequestServiceInterface $fundingRequestService
    ) {}

    public function index(): View
    {
        $school = auth()->user()->school;
        
        // Defensive check
        if (!$school) {
            return redirect()->route('school.dashboard')->with('error', 'School profile not found.');
        }

        $fundingRequests = $school->fundingRequests()
            ->with('studentProfile.user')
            ->latest()
            ->paginate(10);

        return view('school.funding-requests.index', compact('fundingRequests'));
    }

    public function show(FundingRequest $fundingRequest): View
    {
        $this->authorize('view', $fundingRequest);
        
        $fundingRequest->load('studentProfile.user', 'school', 'supportingDocuments');

        return view('school.funding-requests.show', compact('fundingRequest'));
    }

    public function approve(ApproveFundingRequestRequest $request, FundingRequest $fundingRequest): RedirectResponse
    {
        $this->authorize('approve', $fundingRequest);
        
        $this->fundingRequestService->approve($fundingRequest);
        
        return redirect()->route('school.funding-requests.show', $fundingRequest)
            ->with('success', 'Funding Request berhasil disetujui.');
    }

    public function reject(RejectFundingRequestRequest $request, FundingRequest $fundingRequest): RedirectResponse
    {
        $this->authorize('reject', $fundingRequest);
        
        $this->fundingRequestService->reject($fundingRequest, $request->rejection_reason);
        
        return redirect()->route('school.funding-requests.show', $fundingRequest)
            ->with('success', 'Funding Request berhasil ditolak.');
    }
}

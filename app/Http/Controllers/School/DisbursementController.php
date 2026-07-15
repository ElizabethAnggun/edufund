<?php

namespace App\Http\Controllers\School;

use App\Contracts\Services\DisbursementServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use App\Models\FundingRequest;
use App\Models\Milestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisbursementController extends Controller
{
    public function __construct(
        private readonly DisbursementServiceInterface $disbursementService
    ) {}

    /**
     * List all disbursements released by the school to its students.
     */
    public function index(): View
    {
        $school = auth()->user()->school;
        $disbursements = $this->disbursementService->getBySchool($school);

        return view('school.disbursements.index', compact('disbursements'));
    }

    /**
     * Show the milestones available for disbursement for a funding request.
     */
    public function create(FundingRequest $fundingRequest): View
    {
        $this->authorize('view', $fundingRequest);

        $fundingRequest->load([
            'studentProfile.user',
            'campaign',
            'milestones' => function ($query) {
                $query->whereDoesntHave('disbursement')
                    ->whereIn('status', ['submitted', 'verified', 'in_progress', 'pending']);
            },
        ]);

        return view('school.disbursements.create', compact('fundingRequest'));
    }

    /**
     * Release a milestone's funds from the school wallet to the student.
     */
    public function store(Request $request, FundingRequest $fundingRequest, Milestone $milestone): RedirectResponse
    {
        $this->authorize('view', $fundingRequest);

        if ($milestone->funding_request_id !== $fundingRequest->id) {
            abort(404);
        }

        if ($milestone->disbursement) {
            return back()->with('error', 'Dana untuk milestone ini sudah dicairkan.');
        }

        $school = auth()->user()->school;
        $this->disbursementService->release($school, $milestone, $request->input('notes'));

        return redirect()->route('school.funding-requests.show', $fundingRequest)
            ->with('success', 'Dana berhasil dicairkan ke siswa.');
    }

    /**
     * Show a single disbursement detail.
     */
    public function show(Disbursement $disbursement): View
    {
        $school = auth()->user()->school;

        if ($disbursement->school_id !== $school->id) {
            abort(403);
        }

        $disbursement->load([
            'studentProfile.user',
            'fundingRequest',
            'milestone',
            'blockchainTransaction',
        ]);

        return view('school.disbursements.show', compact('disbursement'));
    }
}

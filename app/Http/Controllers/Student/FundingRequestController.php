<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Services\FundingRequestServiceInterface;
use App\Contracts\Services\SupportingDocumentServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\FundingRequestRequest;
use App\Http\Requests\SupportingDocumentRequest;
use App\Models\FundingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FundingRequestController extends Controller
{
    public function __construct(
        private readonly FundingRequestServiceInterface $fundingRequestService,
        private readonly SupportingDocumentServiceInterface $supportingDocumentService
    ) {
    }

    public function index(): View
    {
        $student = auth()->user()->studentProfile;
        $fundingRequests = $this->fundingRequestService->getAllByStudent($student);

        return view('student.funding-requests.index', compact('fundingRequests'));
    }

    public function create(): View
    {
        return view('student.funding-requests.create');
    }

    public function store(FundingRequestRequest $request): RedirectResponse
    {
        $student = auth()->user()->studentProfile;
        $fundingRequest = $this->fundingRequestService->create($student, $request->validated());

        return redirect()->route('student.funding-requests.show', $fundingRequest)
            ->with('success', 'Funding request created successfully.');
    }

    public function show(FundingRequest $fundingRequest): View
    {
        $this->authorize('view', $fundingRequest);
        $documents = $this->supportingDocumentService->getAllByFundingRequest($fundingRequest);

        return view('student.funding-requests.show', compact('fundingRequest', 'documents'));
    }

    public function edit(FundingRequest $fundingRequest): View
    {
        $this->authorize('update', $fundingRequest);

        return view('student.funding-requests.edit', compact('fundingRequest'));
    }

    public function update(FundingRequestRequest $request, FundingRequest $fundingRequest): RedirectResponse
    {
        $this->authorize('update', $fundingRequest);
        $this->fundingRequestService->update($fundingRequest, $request->validated());

        return redirect()->route('student.funding-requests.show', $fundingRequest)
            ->with('success', 'Funding request updated successfully.');
    }

    public function destroy(FundingRequest $fundingRequest): RedirectResponse
    {
        $this->authorize('delete', $fundingRequest);
        $this->fundingRequestService->delete($fundingRequest);

        return redirect()->route('student.funding-requests.index')
            ->with('success', 'Funding request deleted successfully.');
    }

    public function submit(Request $request, FundingRequest $fundingRequest): RedirectResponse
    {
        $this->authorize('update', $fundingRequest);
        $this->fundingRequestService->submit($fundingRequest);

        return redirect()->route('student.funding-requests.show', $fundingRequest)
            ->with('success', 'Funding request submitted successfully.');
    }

    public function uploadDocument(SupportingDocumentRequest $request, FundingRequest $fundingRequest): RedirectResponse
    {
        $this->authorize('update', $fundingRequest);
        $this->supportingDocumentService->upload(
            $fundingRequest,
            $request->file('file'),
            $request->document_type
        );

        return redirect()->route('student.funding-requests.show', $fundingRequest)
            ->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument(FundingRequest $fundingRequest, \App\Models\SupportingDocument $document): RedirectResponse
    {
        $this->authorize('update', $fundingRequest);
        $this->supportingDocumentService->delete($document);

        return redirect()->route('student.funding-requests.show', $fundingRequest)
            ->with('success', 'Document deleted successfully.');
    }
}

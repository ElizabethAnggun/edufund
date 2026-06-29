<?php

namespace App\Contracts\Services;

use App\Models\FundingRequest;
use App\Models\SupportingDocument;
use Illuminate\Http\UploadedFile;

interface SupportingDocumentServiceInterface
{
    public function getAllByFundingRequest(FundingRequest $request): \Illuminate\Database\Eloquent\Collection;
    public function upload(FundingRequest $request, UploadedFile $file, string $documentType): SupportingDocument;
    public function delete(SupportingDocument $document): bool;
}

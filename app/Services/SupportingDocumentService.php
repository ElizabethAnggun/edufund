<?php

namespace App\Services;

use App\Contracts\Services\SupportingDocumentServiceInterface;
use App\Models\FundingRequest;
use App\Models\SupportingDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SupportingDocumentService implements SupportingDocumentServiceInterface
{
    public function getAllByFundingRequest(FundingRequest $request): \Illuminate\Database\Eloquent\Collection
    {
        return $request->supportingDocuments()->get();
    }

    public function upload(FundingRequest $request, UploadedFile $file, string $documentType): SupportingDocument
    {
        $path = $file->store('supporting_documents', 'public');

        return $request->supportingDocuments()->create([
            'document_type' => $documentType,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
    }

    public function delete(SupportingDocument $document): bool
    {
        Storage::disk('public')->delete($document->file_path);
        return $document->delete();
    }
}

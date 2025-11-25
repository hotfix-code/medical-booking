<?php

namespace App\Services;

use App\Models\DocumentType;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;

class DocumentTypeService
{
    public function create(array $data): JsonResponse
    {
        $documentType = DocumentType::create($data);
        return AppResponse::success($documentType, 'Document Type created successfully.');
    }

    public function update(DocumentType $documentType, array $data): JsonResponse
    {
        $documentType->update($data);
        return AppResponse::success($documentType, 'Document Type updated successfully.');
    }

    public function delete(DocumentType $documentType): JsonResponse
    {
        if ($documentType->doctors()->exists())
        {
            return AppResponse::error([
                'users' => 'Cannot delete document type because it has doctors associated with it.'
            ], status: 422);
        }

        if ($documentType->patients()->exists())
        {
            return AppResponse::error([
                'users' => 'Cannot delete document type because it has patients associated with it.'
            ], status: 422);
        }

        $documentType->delete();
        return AppResponse::success($documentType, 'Document Type deleted successfully.');
    }

    public function fetch(DocumentType $documentType): JsonResponse
    {
        return AppResponse::success($documentType);
    }
}

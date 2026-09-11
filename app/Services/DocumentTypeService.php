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
        return AppResponse::success($documentType, __('document_types.flash.created'));
    }

    public function update(DocumentType $documentType, array $data): JsonResponse
    {
        $documentType->update($data);
        return AppResponse::success($documentType, __('document_types.flash.updated'));
    }

    public function delete(DocumentType $documentType): JsonResponse
    {
        if ($documentType->doctors()->exists())
        {
            return AppResponse::error([
                'users' => __('document_types.errors.cannot_delete_doctors'),
            ], status: 422);
        }

        if ($documentType->patients()->exists())
        {
            return AppResponse::error([
                'users' => __('document_types.errors.cannot_delete_patients'),
            ], status: 422);
        }

        $documentType->delete();
        return AppResponse::success($documentType, __('document_types.flash.deleted'));
    }

    public function fetch(DocumentType $documentType): JsonResponse
    {
        return AppResponse::success($documentType);
    }
}

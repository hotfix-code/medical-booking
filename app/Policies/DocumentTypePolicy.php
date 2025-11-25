<?php

namespace App\Policies;

use App\Models\DocumentType;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class DocumentTypePolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'document_type.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocumentType $documentType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'document_type.create',
            'You do not have permission to create document types.'
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentType $documentType): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'document_type.edit',
            'You do not have permission to edit document types.'
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentType $documentType): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'document_type.delete',
            'You do not have permission to delete document types.'
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DocumentType $documentType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DocumentType $documentType): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'document_type.view',
            'You do not have permission to fetch document types.'
        );
    }
}

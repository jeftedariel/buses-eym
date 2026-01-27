<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ResourceStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourceStatusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ResourceStatus');
    }

    public function view(AuthUser $authUser, ResourceStatus $resourceStatus): bool
    {
        return $authUser->can('View:ResourceStatus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ResourceStatus');
    }

    public function update(AuthUser $authUser, ResourceStatus $resourceStatus): bool
    {
        return $authUser->can('Update:ResourceStatus');
    }

    public function delete(AuthUser $authUser, ResourceStatus $resourceStatus): bool
    {
        return $authUser->can('Delete:ResourceStatus');
    }

    public function restore(AuthUser $authUser, ResourceStatus $resourceStatus): bool
    {
        return $authUser->can('Restore:ResourceStatus');
    }

    public function forceDelete(AuthUser $authUser, ResourceStatus $resourceStatus): bool
    {
        return $authUser->can('ForceDelete:ResourceStatus');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ResourceStatus');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ResourceStatus');
    }

    public function replicate(AuthUser $authUser, ResourceStatus $resourceStatus): bool
    {
        return $authUser->can('Replicate:ResourceStatus');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ResourceStatus');
    }

}
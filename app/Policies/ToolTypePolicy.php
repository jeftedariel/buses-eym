<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ToolType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ToolTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ToolType');
    }

    public function view(AuthUser $authUser, ToolType $toolType): bool
    {
        return $authUser->can('View:ToolType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ToolType');
    }

    public function update(AuthUser $authUser, ToolType $toolType): bool
    {
        return $authUser->can('Update:ToolType');
    }

    public function delete(AuthUser $authUser, ToolType $toolType): bool
    {
        return $authUser->can('Delete:ToolType');
    }

    public function restore(AuthUser $authUser, ToolType $toolType): bool
    {
        return $authUser->can('Restore:ToolType');
    }

    public function forceDelete(AuthUser $authUser, ToolType $toolType): bool
    {
        return $authUser->can('ForceDelete:ToolType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ToolType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ToolType');
    }

    public function replicate(AuthUser $authUser, ToolType $toolType): bool
    {
        return $authUser->can('Replicate:ToolType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ToolType');
    }

}
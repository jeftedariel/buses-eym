<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ToolCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ToolCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ToolCategory');
    }

    public function view(AuthUser $authUser, ToolCategory $toolCategory): bool
    {
        return $authUser->can('View:ToolCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ToolCategory');
    }

    public function update(AuthUser $authUser, ToolCategory $toolCategory): bool
    {
        return $authUser->can('Update:ToolCategory');
    }

    public function delete(AuthUser $authUser, ToolCategory $toolCategory): bool
    {
        return $authUser->can('Delete:ToolCategory');
    }

    public function restore(AuthUser $authUser, ToolCategory $toolCategory): bool
    {
        return $authUser->can('Restore:ToolCategory');
    }

    public function forceDelete(AuthUser $authUser, ToolCategory $toolCategory): bool
    {
        return $authUser->can('ForceDelete:ToolCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ToolCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ToolCategory');
    }

    public function replicate(AuthUser $authUser, ToolCategory $toolCategory): bool
    {
        return $authUser->can('Replicate:ToolCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ToolCategory');
    }

}
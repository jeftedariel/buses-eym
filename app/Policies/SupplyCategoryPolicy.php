<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SupplyCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplyCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SupplyCategory');
    }

    public function view(AuthUser $authUser, SupplyCategory $supplyCategory): bool
    {
        return $authUser->can('View:SupplyCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SupplyCategory');
    }

    public function update(AuthUser $authUser, SupplyCategory $supplyCategory): bool
    {
        return $authUser->can('Update:SupplyCategory');
    }

    public function delete(AuthUser $authUser, SupplyCategory $supplyCategory): bool
    {
        return $authUser->can('Delete:SupplyCategory');
    }

    public function restore(AuthUser $authUser, SupplyCategory $supplyCategory): bool
    {
        return $authUser->can('Restore:SupplyCategory');
    }

    public function forceDelete(AuthUser $authUser, SupplyCategory $supplyCategory): bool
    {
        return $authUser->can('ForceDelete:SupplyCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SupplyCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SupplyCategory');
    }

    public function replicate(AuthUser $authUser, SupplyCategory $supplyCategory): bool
    {
        return $authUser->can('Replicate:SupplyCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SupplyCategory');
    }

}
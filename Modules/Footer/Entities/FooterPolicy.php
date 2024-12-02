<?php

namespace Modules\Footer\Entities;

use App\Models\User;
use Modules\Footer\Entities\Footer;
use Illuminate\Auth\Access\HandlesAuthorization;

class FooterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_footer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Footer $footer): bool
    {
        return $user->can('view_footer');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_footer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Footer $footer): bool
    {
        return $user->can('update_footer');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Footer $footer): bool
    {
        return $user->can('delete_footer');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_footer');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Footer $footer): bool
    {
        return $user->can('force_delete_footer');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_footer');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Footer $footer): bool
    {
        return $user->can('restore_footer');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_footer');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Footer $footer): bool
    {
        return $user->can('replicate_footer');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_footer');
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view_subjects');
    }

    public function view(User $user, Subject $subject)
    {
        return $user->tenant_id === $subject->tenant_id 
               && $user->hasPermission('view_subjects');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_subjects');
    }

    public function update(User $user, Subject $subject)
    {
        return $user->tenant_id === $subject->tenant_id 
               && $user->hasPermission('edit_subjects');
    }

    public function delete(User $user, Subject $subject)
    {
        return $user->tenant_id === $subject->tenant_id 
               && $user->hasPermission('delete_subjects');
    }
}
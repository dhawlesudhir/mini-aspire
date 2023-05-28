<?php

namespace App\Policies;

use App\Models\LoanAccount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanAccontPolicy
{
    use HandlesAuthorization;


    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->type == "ADMIN") {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // return $user->type == 'ADMIN';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanAccount  $loanAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LoanAccount $loanAccount)
    {
        return $user->id === $loanAccount->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanAccount  $loanAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LoanAccount $loanAccount)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanAccount  $loanAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LoanAccount $loanAccount)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanAccount  $loanAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, LoanAccount $loanAccount)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LoanAccount  $loanAccount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, LoanAccount $loanAccount)
    {
        //
    }
}

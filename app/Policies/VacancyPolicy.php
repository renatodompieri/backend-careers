<?php

namespace App\Policies;

use App\User;
use App\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        return $user->can('access-vacancy');
    }

    /**
     * Determine whether the user can view all the vacancy.
     *
     * @param  \App\User  $user
     * @param  \App\Vacancy  $vacancy
     * @return mixed
     */
    public function view(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create vacancys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the vacancy.
     *
     * @param  \App\User  $user
     * @param  \App\Vacancy  $vacancy
     * @return mixed
     */
    public function show(User $user, Vacancy $vacancy)
    {
        return $vacancy->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the vacancy.
     *
     * @param  \App\User  $user
     * @param  \App\Vacancy  $vacancy
     * @return mixed
     */
    public function update(User $user, Vacancy $vacancy)
    {
        return $vacancy->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the vacancy.
     *
     * @param  \App\User  $user
     * @param  \App\Vacancy  $vacancy
     * @return mixed
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        return $vacancy->user_id === $user->id;
    }
}

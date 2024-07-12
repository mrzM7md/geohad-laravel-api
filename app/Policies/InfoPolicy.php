<?php

namespace App\Policies;

use App\Models\Info;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InfoPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === "admin"
            ? Response::allow()
            : Response::deny("ليس لديك الصلاحية لإنشاء معلومة جديدة")
        ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Info $category)
    {
        return $user->role === "admin"
        ? Response::allow()
        : Response::deny("ليس لديك الصلاحية لتعديل أي معلومة")
    ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Info $category)
    {
        return $user->role === "admin"
        ? Response::allow()
        : Response::deny("ليس لديك الصلاحية لحذف أي معلومة")
    ;
    }
}

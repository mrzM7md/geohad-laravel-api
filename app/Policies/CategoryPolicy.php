<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === "admin"
            ? Response::allow()
            : Response::deny("ليس لديك الصلاحية لإنشاء عنوان جديد")
        ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category)
    {
        return $user->role === "admin"
        ? Response::allow()
        : Response::deny("ليس لديك الصلاحية لتعديل أي عنوان")
    ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category)
    {
        return $user->role === "admin"
        ? Response::allow()
        : Response::deny("ليس لديك الصلاحية لحذف أي عنوان")
    ;
    }
}

<?php

namespace App\Policies;

use App\Notification;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Notification $notification)
    {
        return $user->id === $notification->id_usuario;
    }

    public function read(User $user, Notification $notification)
    {
        return $user->id === $notification->id_usuario;
    }
}

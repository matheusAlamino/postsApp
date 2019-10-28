<?php

namespace App\Policies;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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

    public function edit(User $user, Comment $comment, Post $post)
    {
        return $user->id === $comment->id_usuario || $user->id === $post->id_usuario;
    }
}

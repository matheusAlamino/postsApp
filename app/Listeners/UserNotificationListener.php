<?php

namespace App\Listeners;

use App\Events\CommentCreatedEvent;
use App\Notification;
use App\Post;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentCreatedEvent  $event
     * @return void
     */
    public function handle(CommentCreatedEvent $event)
    {
        $comment = $event->getComment();

        $post = Post::find($comment->id_post);

        if ($post->id_usuario != $comment->id_usuario) {
            Notification::create([
                'id_usuario' => $post->id_usuario,
                'text' => 'Existe um novo comentario na postagem ' . $post->id,
                'seen' => false
            ]);
        }
    }
}

<?php

namespace App;

use App\Events\CommentCreatedEvent;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'description', 'id_post', 'id_usuario'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo((User::class));
    }

    protected $dispatchesEvents = [
        //'updated' => LogUpdatedEvent::class,
        'created' => CommentCreatedEvent::class
    ];
}

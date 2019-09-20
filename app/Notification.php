<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'text', 'id_usuario'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

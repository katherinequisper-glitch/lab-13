<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content', 'user_id', 'post_id'];

    // Un comentario pertenece a una publicación
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Un comentario pertenece a un usuario (quién lo escribió)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
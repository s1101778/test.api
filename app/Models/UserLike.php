<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLike extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function Post()
    {
        return $this->belongsTo(Post::class);
    }
    public function Comment()
    {
        return $this->belongsTo(Comment::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}

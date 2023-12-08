<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UvaTopic extends model
{
    use HasFactory;
    protected $guarded=[];

    public function Post()
    {
        return $this->hasMany(Post::class);
    }
    public static function get_uva_topic_id($serial)
    {
        $result=UvaTopic::where('serial',$serial)->first()->id;
        return $result;
    }
}
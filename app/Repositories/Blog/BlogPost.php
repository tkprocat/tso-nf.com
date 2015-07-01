<?php
namespace LootTracker\Repositories\Blog;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{

    protected $table = 'posts';

    public function comments()
    {
        return $this->hasMany('LootTracker\Repositories\Blog\BlogComment', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo('LootTracker\Repositories\User\User');
    }
}
<?php
namespace LootTracker\Repositories\Blog;

class BlogComment extends \Eloquent
{

    protected $table = 'comments';

    public function post()
    {
        return $this->belongsTo('LootTracker\Repositories\Blog\BlogPost', 'post_id');
    }
}
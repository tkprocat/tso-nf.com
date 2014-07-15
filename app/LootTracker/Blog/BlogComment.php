<?php
namespace LootTracker\Blog;

class BlogComment extends \Eloquent {

	protected $table = 'comments';

    public function post() {
        return $this->belongsTo('post', 'post_id');
    }
}
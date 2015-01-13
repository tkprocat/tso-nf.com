<?php
namespace LootTracker\Blog;

class BlogPost extends \Eloquent {

	protected $table = 'posts';

    public function comments() {
        return $this->hasMany('Post', 'post_id');
    }

    public function user() {
        return $this->belongsTo('User');
    }
}
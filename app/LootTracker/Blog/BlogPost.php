<?php
namespace LootTracker\Blog;

class BlogPost extends \Eloquent {

	protected $table = 'posts';

    public function comments() {
        return $this->hasMany('LootTracker\Blog\BlogComment', 'post_id');
    }

    public function user() {
        return $this->belongsTo('User');
    }
}
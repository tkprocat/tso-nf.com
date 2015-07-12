<?php namespace LootTracker\Repositories\Blog;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BlogPost
 * @package LootTracker\Repositories\Blog
 */
class BlogPost extends Model
{

    /**
     * @var string
     */
    protected $table = 'posts';


    /**
     * @return \LootTracker\Repositories\Blog\BlogComment
     */
    public function comments()
    {
        return $this->hasMany('LootTracker\Repositories\Blog\BlogComment', 'post_id');
    }


    /**
     * @return \LootTracker\Repositories\User\User
     */
    public function user()
    {
        return $this->belongsTo('LootTracker\Repositories\User\User');
    }
}

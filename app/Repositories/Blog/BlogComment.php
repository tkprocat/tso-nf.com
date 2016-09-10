<?php namespace LootTracker\Repositories\Blog;

/**
 * Class BlogComment
 * @package LootTracker\Repositories\Blog
 */
class BlogComment extends \Eloquent
{

    /**
     * @var string
     */
    protected $table = 'comments';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('LootTracker\Repositories\Blog\BlogPost', 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('LootTracker\Repositories\User\User');
    }
}

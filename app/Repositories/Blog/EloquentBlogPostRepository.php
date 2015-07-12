<?php namespace LootTracker\Repositories\Blog;

use Auth;
use Illuminate\Support\Str;

/**
 * Class EloquentBlogPostRepository
 * @package LootTracker\Repositories\Blog
 */
class EloquentBlogPostRepository implements BlogPostInterface
{

    /**
     * @return mixed
     */
    public function all()
    {
        return BlogPost::with('comments', 'user')->orderBy('created_at', 'desc')->get();
    }


    /**
     * @param $slug
     *
     * @return mixed
     */
    public function findSlug($slug)
    {
        return BlogPost::where('slug', $slug)->firstOrFail();
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function byId($id)
    {
        return BlogPost::findOrFail($id);
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function findComments($id)
    {
        return $blogPost = $this->byId($id)->comments;
    }


    /**
     * @param $data
     */
    public function create($data)
    {
        $blogPost          = new BlogPost;
        $blogPost->user_id = Auth::user()->id;
        $blogPost->title   = e($data['title']);
        $blogPost->slug    = Str::slug($blogPost->title);
        $blogPost->content = e($data['content']);
        $blogPost->save();
    }


    /**
     * @param $id
     * @param $data
     */
    public function update($id, $data)
    {
        $blogPost          = $this->byId($id);
        $blogPost->title   = e($data['title']);
        $blogPost->slug    = Str::slug($blogPost->title);
        $blogPost->content = e($data['content']);
        $blogPost->update();
    }


    /**
     * @param $id
     */
    public function delete($id)
    {
        $blogPost = $this->byId($id);
        $blogPost->delete();
    }
}

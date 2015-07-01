<?php namespace LootTracker\Repositories\Blog;

use Auth;
use Illuminate\Support\Str;

class EloquentBlogPostRepository implements BlogPostInterface
{
    public function all()
    {
        return BlogPost::with('comments', 'user')->orderBy('created_at', 'desc')->get();
    }

    public function findPage($page, $limit)
    {
        $result = new \StdClass;
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = array();
        $query = BlogPost::orderBy('created_at', 'desc');
        $blogPosts = $query->skip($limit * ($page - 1))->take($limit)->get();
        $result->items = $blogPosts->all();
        $result->totalItems = $blogPosts->count();

        return $result;
    }

    public function findSlug($slug)
    {
        return BlogPost::where('slug', $slug)->firstOrFail();
    }


    public function byId($id)
    {
        return BlogPost::findOrFail($id);
    }

    public function findComments($id)
    {
        return $blogPost = $this->byId($id)->comments;
    }

    public function create($data)
    {
        $blogPost = new BlogPost;
        $blogPost->user_id = Auth::user()->id;
        $blogPost->title = e($data['title']);
        $blogPost->slug = Str::slug($blogPost->title);
        $blogPost->content = e($data['content']);
        $blogPost->save();
    }

    public function update($id, $data)
    {
        $blogPost = $this->byId($id);
        $blogPost->title = e($data['title']);
        $blogPost->slug = Str::slug($blogPost->title);
        $blogPost->content = e($data['content']);
        $blogPost->update();
    }

    public function delete($id)
    {
        $blogPost = $this->byId($id);
        $blogPost->delete();
    }
}

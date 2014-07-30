<?php
namespace LootTracker\Blog;
use Illuminate\Support\Str;

class DbBlogPostRepository implements BlogPostInterface {

    protected $blogPost;
    protected $comments;
    public $validator;
    protected $user;

    public function __construct(BlogPost $blogPost, BlogCommentInterface $comment, BlogPostFormValidator $validator) {
        $this->blogPost = $blogPost;
        $this->comments = $comment;
        $this->validator = $validator;
    }

    public function all()
    {
        return $this->blogPost->all();
    }

    public function create($input)
    {
        return $this->blogPost->create($input)->toArray();
    }

    public function findPage($page, $limit)
    {
        $result = new \StdClass;
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = array();
        $query = $this->blogPost->orderBy('created_at', 'desc');
        $blogPosts = $query->skip ($limit * ($page-1))->take($limit)->get();
        $result->items = $blogPosts->all();
        $result->totalItems = $blogPosts->count();
        return $result;
    }

    public function findSlug($slug)
    {
        return $this->blogPost->where('slug', $slug)->first();
    }

    public function delete($id)
    {
        return $this->blogPost->find($id)->delete();
    }

    public function saveBlogPost($data)
    {
        $this->blogPost->user_id = $data['user_id'];
        $this->blogPost->title = e($data['title']);
        $this->blogPost->slug = Str::slug($this->blogPost->title);
        $this->blogPost->content = e($data['content']);
        $this->blogPost->save();
    }

    public function updateBlogPost($id, $data) {
        $this->blogPost = $this->blogPost->find($id);
        $this->blogPost->user_id = $data['user_id'];
        $this->blogPost->title = e($data['title']);
        $this->blogPost->slug = Str::slug($this->blogPost->title);
        $this->blogPost->content = e($data['content']);
        $this->blogPost->update();
    }

    public function findId($id)
    {
        return $this->blogPost->find($id);
    }

    public function findComments($id)
    {
        return $this->comments->findCommentsForPost($id);
    }
}

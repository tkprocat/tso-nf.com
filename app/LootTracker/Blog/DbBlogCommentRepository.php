<?php
namespace LootTracker\Blog;

use Illuminate\Database\Eloquent\Model;

class DbBlogCommentRepository implements BlogCommentInterface
{
    protected $comment;
    public $validator;

    public function __construct(Model $comment, BlogCommentFormValidator $validator)
    {
        $this->comment = $comment;
        $this->validator = $validator;
    }

    public function findCommentsForPost($blogPostId)
    {
        return $this->comment->where('post_id', $blogPostId)->get();
    }

    public function saveBlogComment($data)
    {
        $this->comment->post_id = $data['post_id'];
        $this->comment->user_id = $data['user_id'];
        $this->comment->content = $data['content'];
        $this->comment->save();
    }

    public function updateBlogComment($id, $data)
    {
        $this->comment = $this->comment->find($id);
        $this->comment->post_id = $data['post_id'];
        $this->comment->user_id = $data['user_id'];
        $this->comment->content = $data['content'];
        $this->comment->update();
    }

    public function find($id)
    {
        return $this->comment->findOrFail($id);
    }
}
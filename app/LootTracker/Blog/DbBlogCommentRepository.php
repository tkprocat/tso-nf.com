<?php
namespace LootTracker\Blog;

use LootTracker\Blog\BlogComment;
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

    public function create($data)
    {
        $this->comment->post_id = $data['post_id'];
        $this->comment->user_id = $data['user_id'];
        $this->comment->content = $data['content'];
        $this->comment->save();
    }

    public function update($id, $data)
    {
        $this->comment = $this->comment->find($id);
        $this->comment->user_id = $data['user_id'];
        $this->comment->content = $data['content'];
        $this->comment->update();
        return $this->comment;
    }

    public function delete($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->delete();
    }

    public function find($id)
    {
        return BlogComment::findOrFail($id);
    }
}
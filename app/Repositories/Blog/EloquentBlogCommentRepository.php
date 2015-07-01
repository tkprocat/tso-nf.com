<?php namespace LootTracker\Repositories\Blog;

use Illuminate\Database\Eloquent\Model;

class EloquentBlogCommentRepository implements BlogCommentInterface
{
    public function findCommentsForPost($blogPostId)
    {
        return BlogComment::where('post_id', $blogPostId)->get();
    }

    public function create($data)
    {
        $blogComment = new BlogComment();
        $blogComment->post_id = $data['post_id'];
        $blogComment->user_id = $data['user_id'];
        $blogComment->content = $data['content'];
        $blogComment->save();
    }

    public function update($id, $data)
    {
        $blogComment = $this->byId($id);
        $blogComment->content = $data['content'];
        $blogComment->update();

        return $blogComment;
    }

    public function delete($id)
    {
        $comment = $this->byId($id);
        $comment->delete();
    }

    public function byId($id)
    {
        return BlogComment::findOrFail($id);
    }
}
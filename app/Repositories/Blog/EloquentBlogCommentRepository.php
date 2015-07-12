<?php namespace LootTracker\Repositories\Blog;

/**
 * Class EloquentBlogCommentRepository
 * @package LootTracker\Repositories\Blog
 */
class EloquentBlogCommentRepository implements BlogCommentInterface
{

    /**
     * @param $blogPostId
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findCommentsForPost($blogPostId)
    {
        return BlogComment::where('post_id', $blogPostId)->get();
    }


    /**
     * @param $data
     */
    public function create($data)
    {
        $blogComment = new BlogComment();
        $blogComment->post_id = $data['post_id'];
        $blogComment->user_id = $data['user_id'];
        $blogComment->content = $data['content'];
        $blogComment->save();
    }


    /**
     * @param $id
     * @param $data
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function update($id, $data)
    {
        $blogComment = $this->byId($id);
        $blogComment->content = $data['content'];
        $blogComment->update();

        return $blogComment;
    }


    /**
     * @param $id
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        $comment = $this->byId($id);
        $comment->delete();
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return BlogComment::findOrFail($id);
    }
}

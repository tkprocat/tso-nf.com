<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Requests\BlogCommentRequest;
use LootTracker\Repositories\Blog\BlogCommentInterface;
use LootTracker\Repositories\Blog\BlogPostInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class BlogCommentController
 * @package LootTracker\Http\Controllers
 */
class BlogCommentController extends Controller
{

    /**
     * @var BlogPostInterface
     */
    protected $blogPostRepo;

    /**
     * @var BlogCommentInterface
     */
    protected $blogCommentRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * @param BlogPostInterface    $blogPost
     * @param BlogCommentInterface $blogComment
     * @param UserInterface        $user
     */
    public function __construct(BlogPostInterface $blogPost, BlogCommentInterface $blogComment, UserInterface $user)
    {
        $this->blogPostRepo    = $blogPost;
        $this->blogCommentRepo = $blogComment;
        $this->userRepo        = $user;
    }


    /**
     * Show the form for creating a blog comment.
     *
     * @param $post_id
     *
     * @return \Illuminate\View\View
     */
    public function create($post_id)
    {
        try {
            $this->blogPostRepo->byId($post_id);
        } catch (ModelNotFoundException $ex) {
            return Redirect::to('blog')->with('error', 'Blog post not found.');
        }

        return view('blog.comment.create')->with('post_id', $post_id);
    }


    /**
     * Store a newly created comment.
     *
     * @param BlogCommentRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(BlogCommentRequest $request)
    {
        //Passed validation, store the blog post.
        $this->blogCommentRepo->create($request->data);
        $post = $this->blogPostRepo->findId($request->data['post_id']);

        return Redirect::to('blog/' . $post->slug)->with('success', 'Comment posted successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int   $comment_id
     *
     * @return \Illuminate\View\View
     */
    public function edit($comment_id)
    {
        $comment = $this->blogCommentRepo->byId($comment_id);

        //Check if it's the comment poster or an admin trying to editing otherwise fail.
        if (!$this->userRepo->getUser()->can('admin-blog') && ($this->userRepo->getUser()->id !== $comment->user_id)) {
            return Redirect::back()->withError('You do not have permission to edit this comment!');
        }

        return view('blog.comment.edit')->with('comment', $comment);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param BlogCommentRequest $request
     * @param                    $comment_id
     *
     * @return \Illuminate\View\View
     */
    public function update(BlogCommentRequest $request, $comment_id)
    {
        //Check if the user has permission to post news.
        $comment = $this->blogCommentRepo->byId($comment_id);

        $user = $this->userRepo->getUser();

        //Bail if it's not the user nor an admin editing.
        if ((!$user->can('admin-blog')) && ($comment->user_id != $user->id)) {
            return Redirect::back()->withError('You do not have permission to edit this comment!');
        }

        $comment = $this->blogCommentRepo->update($comment_id, $request->all());

        return Redirect::to('blog/' . $comment->post->slug)->with('success', 'Comment updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $comment_id
     *
     * @return \Illuminate\View\View|void
     */
    public function destroy($comment_id)
    {
        //Check if the user has permission to post news.
        $comment = $this->blogCommentRepo->byId($comment_id);
        $user    = $this->userRepo->getUser();

        //Check if the user is the comment poster
        if ($user->id !== $comment->user_id && !$user->can('admin-blog')) {
            return Redirect::back()->withError('You do not have permission to delete this comment!');
        }

        $this->blogCommentRepo->delete($comment_id);

        return Redirect::back()->with(array('success' => 'Blog deleted.'));
    }
}

<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Requests\BlogCommentRequest;
use LootTracker\Repositories\Blog\BlogCommentInterface;
use LootTracker\Repositories\Blog\BlogPostInterface;
use LootTracker\Repositories\User\UserInterface;

class BlogCommentController extends Controller
{

    protected $blogPost;
    protected $blogComment;
    protected $user;

    public function __construct(BlogPostInterface $blogPost, BlogCommentInterface $blogComment, UserInterface $user)
    {
        $this->blogPost = $blogPost;
        $this->blogComment = $blogComment;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    }


    /**
     * Show the form for creating a blog comment.
     *
     * @param $post_id
     * @return Response
     */
    public function create($post_id)
    {
        try {
            //This will actually trow an exception if the blog post does not exist,
            //but adding the code in case it changes.
            if ($this->blogPost->byId($post_id) == null) {
                return Redirect::to('blog')->with('error', 'Blog post not found.');
            }
        } catch (ModelNotFoundException $ex) {
            return Redirect::to('blog')->with('error', 'Blog post not found.');
        }

        return view('blog.comment.create')->with('post_id', $post_id);
    }


    /**
     * Store a newly created comment.
     *
     * @return Response
     */
    public function store(BlogCommentRequest $request)
    {
        //Check if the user has permission to post news.
        if (!$this->user->check()) {
            return Redirect::to('auth\login');
        }

        $data = $request->data;
        $data['user_id'] = $this->user->getUser()->id; //This feels wrong....

        if ($this->blogComment->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->blogComment->create($data);
            $post = $this->blogPost->findId($data['post_id']);

            return Redirect::to('blog/' . $post->slug)->with('success', 'Comment posted successfully');
        } else {
            //Failed validation
            return Redirect::back()->withInput()->withErrors($this->blogComment->validator->errors());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param $post_id
     * @param $comment_id
     * @return Response
     * @internal param int $id
     */
    public function show($comment_id)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param $post_id
     * @param $comment_id
     * @return Response
     * @internal param int $id
     */
    public function edit($comment_id)
    {
        $comment = $this->blogComment->byId($comment_id);

        //Check if it's the comment poster or an admin trying to editing otherwise fail.
        if (!$this->user->getUser()->can('admin-blog') && !$this->user->getUser()->id === $comment->user_id) {
            return Redirect::back()->withError('You do not have permission to edit this comment!');
        }

        return view('blog.comment.edit')->with('comment', $comment);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(BlogCommentRequest $request, $comment_id)
    {
        //Check if the user has permission to post news.
        $comment = $this->blogComment->byId($comment_id);

        $user = $this->user->getUser();
        //Bail if it's not the user nor an admin editing.
        if ((!$user->can('admin-blog')) && ($comment->user_id != $user->id)) {
            abort('403');
        }

        $comment = $this->blogComment->update($comment_id, $request->all());
        return Redirect::to('blog/' . $comment->post->slug)->with('success', 'Comment updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($comment_id)
    {
        //Check if the user has permission to post news.
        $comment = $this->blogComment->byId($comment_id);
        $user = $this->user->getUser();
        //Check if the user is the comment poster
        if ($user->id === $comment->user_id) {
            //Check the user still has permission to delete post.
            if (!$user->can('post-blog-comment') && !$user->can('admin-blog')) {
                abort('403');
            }
        } else {
            //It's not the comment poster, check if the user has admin permission
            if (!$user->can('admin-blog')) {
                abort('403');
            }
        }

        $this->blogComment->delete($comment_id);
    }
}
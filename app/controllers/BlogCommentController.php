<?php
use LootTracker\Blog\BlogCommentInterface;
use LootTracker\Blog\BlogPostInterface;
use \Authority\Repo\User\UserInterface;

class BlogCommentController extends \BaseController
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
        if (!$this->blogPost->findId($post_id))
            return Redirect::to('blog')->with('error', 'Blog post not found.');

        return View::make('blog.comment.create')->with('post_id', $post_id);
    }


    /**
     * Store a newly created comment.
     *
     * @return Response
     */
    public function store()
    {
        //Check if the user has permission to post news.
        if (!$this->user->check())
            return Redirect::to('login');

        $data = Input::all();
        $data['user_id'] = $this->user->getUser()->id; //This feels wrong....

        if ($this->blogComment->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->blogComment->create($data);
            $post = $this->blogPost->findId($data['post_id']);
            return Redirect::to('blog/'.$post->slug)->with('success', 'Comment posted successfully');
        } else {
            //Failed validation
            return Redirect::back()->withInput()->withErrors($this->blogComment->validator->errors());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($post_id, $comment_id)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($post_id, $comment_id)
    {
        $comment = $this->blogComment->find($comment_id);
        return View::make('blog.comment.edit')->with('comment', $comment);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($post_id, $comment_id)
    {
        //Check if the user has permission to post news.
        $comment = $this->blogComment->find($comment_id);

        $user = $this->user->getUser();
        //Bail if it's not the user nor an admin editing.
        if ((!$user->hasAccess('admin')) && ($comment->user_id != $user->id))
            return Redirect::to('login');

        $comment = Input::all();
        $comment['user_id'] = $user->id; //This feels wrong....

        if ($this->blogComment->validator->with($comment)->passes()) {
            //Passed validation, store the blog post.
            $comment = $this->blogComment->update($comment_id, $comment);
            return Redirect::to('blog/'.$comment->post->slug)->with('success', 'Comment updated successfully');
        } else {
            //Failed validation
            return Redirect::back()->withErrors($this->blogComment->validator->errors())->withInput($comment);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($post_id, $comment_id)
    {
        //Check if the user has permission to post news.
        $user = $this->user->getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $this->blogComment->delete($comment_id);
    }
}
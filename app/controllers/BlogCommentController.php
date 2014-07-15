<?php
use LootTracker\Blog\BlogCommentInterface;
use LootTracker\Blog\BlogPostInterface;
use \Authority\Repo\User\UserInterface;

class BlogCommentController extends \BaseController
{

    protected $blogPost;
    protected $blogComment;
    protected $user;

    function __construct(BlogPostInterface $blogPost, BlogCommentInterface $blogComment, \Authority\Repo\User\UserInterface $user)
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        if (!$this->blogPost->findId($id))
            return Redirect::to('blog')->with('error', 'Blog post not found.');

        return View::make('blog.comment.create')->with('post_id', $id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //Check if the user has permission to post news.
        if (!Sentry::check())
            return Redirect::to('login');

        $data = Input::all();
        $data['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->blogComment->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->blogComment->saveBlogComment($data);
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
    public function show($id)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $comment = $this->blogComment->findId($id);
        return View::make('blog.comment.edit')->with('comment', $comment);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        //Check if the user has permission to post news.
        $comment = $this->blogComment->find(Input::get('id'));

        $user =  Sentry::getUser();
        //Bail if it's not the user nor an admin editing.
        if ((!$user->hasAccess('admin')) && ($comment->user_id != Sentry::getUser()->id))
            return Redirect::to('login');

        $comment = Input::all();
        $comment['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->blogComment->validator->with($comment)->passes()) {
            //Passed validation, store the blog post.
            $this->blogComment->updateBlogComment($comment['id'], $comment);
            return Redirect::to('blog')->with('success', 'Comment updated successfully');
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
    public function destroy($id)
    {
        //
    }
}

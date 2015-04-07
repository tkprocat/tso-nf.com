<?php
use LootTracker\Blog\BlogPostInterface;
use \Authority\Repo\User\UserInterface;

class BlogPostController extends \BaseController
{

    protected $blogPost;
    protected $user;

    function __construct(BlogPostInterface $blogPost, UserInterface $user)
    {
        $this->blogPost = $blogPost;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page = Input::get('page', 1);
        $blogsPerPage = 10;
        $pagiData = $this->blogPost->findPage($page, $blogsPerPage);

        $blogs = Paginator::make(
           $pagiData->items,
           $pagiData->totalItems,
           $blogsPerPage
        );

        return View::make('blog.post.index')->with('blogs', $blogs);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('blog.post.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //Check if the user has permission to post news.
        $user =  Sentry::getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $data = Input::all();
        $data['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->blogPost->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->blogPost->saveBlogPost($data);
            return Redirect::to('blog')->with('success', 'Blog posted successfully');
        } else {
            //Failed validation
            return Redirect::to('blog/create')->withInput()->withErrors($this->blogPost->validator->errors());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param $slug
     * @internal param int $id
     * @return Response
     */
    public function show($slug)
    {
        $post = $this->blogPost->findSlug($slug);
        $comments = $this->blogPost->findComments($post->id);
        return View::make('blog.post.show')->with(array('post' => $post, 'comments' => $comments));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $blog = $this->blogPost->findId($id);
        if (is_null($blog))
            return Redirect::to('blog')->with('error', 'Blog post not found!');
        return View::make('blog.post.edit')->with('blog', $blog);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        //Check if the user has permission to post news.
        $user =  Sentry::getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $post = Input::all();
        $post['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->blogPost->validator->with($post)->passes()) {
            //Passed validation, store the blog post.
            $this->blogPost->updateBlogPost($post['id'], $post);
            return Redirect::to('blog')->with('success', 'Blog updated successfully');
        } else {
            //Failed validation
            return Redirect::back()->withErrors($this->blogPost->validator->errors())->withInput($post);
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
        //Check if the user has permission to post news.
        $user =  Sentry::getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $this->blogPost->delete($id);
    }
}

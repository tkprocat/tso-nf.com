<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use LootTracker\Http\Requests\BlogPostRequest;
use LootTracker\Repositories\Blog\BlogPostInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class BlogPostController
 * @package LootTracker\Http\Controllers
 */
class BlogPostController extends Controller
{

    /**
     * @var BlogPostInterface
     */
    protected $blogPostRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * @param BlogPostInterface $blogPostRepo
     * @param UserInterface     $user
     */
    public function __construct(BlogPostInterface $blogPostRepo, UserInterface $user)
    {
        $this->blogPostRepo = $blogPostRepo;
        $this->userRepo     = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page              = \Input::get('page', 1);
        $blogsPostsPerPage = 10;
        $blogs             = new Paginator($this->blogPostRepo->all(), $blogsPostsPerPage, $page);

        return view('blog.post.index')->with('blogs', $blogs);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('blog.post.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param BlogPostRequest $request
     *
     * @return Response
     */
    public function store(BlogPostRequest $request)
    {
        $this->blogPostRepo->create($request);

        return Redirect::to('blog')->with('success', 'Post created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param $slug
     *
     * @internal param int $id
     * @return Response
     */
    public function show($slug)
    {
        try {
            $post     = $this->blogPostRepo->findSlug($slug);
            $comments = $this->blogPostRepo->findComments($post->id);
        } catch (ModelNotFoundException $ex) {
            return Redirect::to('blog')->withError('Blog post not found!');
        }

        return view('blog.post.show')->with(['post' => $post, 'comments' => $comments]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $blog = $this->blogPostRepo->byId($id);
        } catch (ModelNotFoundException $ex) {
            return Redirect::to('blog')->withError('Post not found!');
        }

        return view('blog.post.edit')->with('blog', $blog);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param BlogPostRequest $request
     * @param int             $id
     *
     * @return Response
     */
    public function update(BlogPostRequest $request, $id)
    {
        $this->blogPostRepo->update($id, $request->all());

        return Redirect::to('blog')->with('success', 'Post updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->blogPostRepo->delete($id);
    }
}

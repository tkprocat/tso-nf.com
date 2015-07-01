<?php namespace LootTracker\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use LootTracker\Http\Requests\BlogPostRequest;
use LootTracker\Repositories\Blog\BlogPostInterface;
use LootTracker\Repositories\User\UserInterface;

class BlogPostController extends Controller
{

    protected $blogPostRepo;
    protected $user;

    function __construct(BlogPostInterface $blogPostRepo, UserInterface $user)
    {
        $this->blogPostRepo = $blogPostRepo;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page = \Input::get('page', 1);
        $blogsPostsPerPage = 10;
        $blogs = new Paginator($this->blogPostRepo->all(), $blogsPostsPerPage, $page);

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
     * @return Response
     */
    public function store(BlogPostRequest $request)
    {
        $this->blogPostRepo->create($request);

        return redirect('blog');
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
        try {
            $post = $this->blogPostRepo->findSlug($slug);
            $comments = $this->blogPostRepo->findComments($post->id);
        } catch (ModelNotFoundException $ex) {
            return redirect('blog')->withError('Blog post not found!');
        }

        return view('blog.post.show')->with(array('post' => $post, 'comments' => $comments));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        try {
            $blog = $this->blogPostRepo->byId($id);
        } catch (ModelNotFoundException $ex) {
            return redirect('blog')->withError('Blog post not found!');
        }

        return view('blog.post.edit')->with('blog', $blog);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(BlogPostRequest $request, $id)
    {
        $this->blogPostRepo->update($id, $request->all());

        return Redirect::to('blog')->with('success', 'Blog updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->blogPostRepo->delete($id);
    }
}

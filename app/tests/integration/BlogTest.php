<?php

class BlogTest extends TestCase
{

    protected $fake;
    protected $blogCommentRepository;
    protected $blogPostRepository;
    protected $user;

    public function setUp()
    {
        parent::setUp();
        Route::enableFilters();
        $this->fake = Faker\Factory::create();
        $this->blogCommentRepository = App::make('LootTracker\Blog\BlogCommentInterface');
        $this->blogPostRepository = App::make('LootTracker\Blog\BlogPostInterface');
        $this->user = App::make('Authority\Repo\User\UserInterface');
    }

    public function test_create_blog_post_as_guest()
    {
        $this->call('GET', 'blog/create');
        $this->assertRedirectedTo('login');
    }

    /** @test */
    public function can_load_create_new_blog_post()
    {
        //Log in
        $this->login();

        $this->call('GET', 'blog/create');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_not_load_create_new_blog_post_when_not_logged_in()
    {
        $this->call('GET', 'blog/create');

        //Check we get redirected to the login page.
        $this->assertRedirectedToRoute('login');
    }

    /** @test */
    public function can_load_create_new_blog_comment()
    {
        $this->login();

        //Post with ID 1 haven't been created so it should redirect to the blog frontpag with an error.
        $this->call('GET', 'blog/1/comment/create');
        $this->assertRedirectedTo('blog', array('error' => 'Blog post not found.'));

        //Create a blog post and try again.
        $this->create_blog_post();
        $this->call('GET', '/blog/1/comment/create');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_not_load_create_new_blog_comment_when_not_logged_in()
    {
        $this->call('GET', 'blog/1/comment/create');

        //Check we get redirected to the login page.
        $this->assertRedirectedToRoute('login');
    }

    /** @test */
    public function fail_to_create_comment_if_blog_does_not_exists()
    {
        //Log in
        $this->login();
        $this->call('GET', 'blog/99999999999/comment/create');

        $this->assertRedirectedToRoute('blog', array(), array('error' => 'Blog post not found.'));
    }

    /** @test */
    public function can_create_new_blog_post()
    {
        //Log in
        $this->login();

        $title = $this->fake->sentence;
        $post = array(
            'id' => 1,
            'title' => $title,
            'slug' => \Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => $this->user->id
        );

        $this->call('POST', '/blog', $post);

        $this->assertRedirectedToRoute('blog', array(), array('success' => 'Blog posted successfully'));

        //Check that it's saved.
        $blogPostCount = $this->blogPostRepository->all()->count();
        $this->assertEquals(1, $blogPostCount);
    }

    /** @test */
    public function fails_creating_blog_post_with_invalid_data()
    {
        //Log in
        $this->login();

        $post = array(
            'title' => $this->fake->title,
        );
        $this->call('POST', '/blog', $post);

        $this->assertRedirectedTo('blog/create', array(), array('success' => 'Blog posted successfully'));
    }

    /** @test */
    public function can_see_detailed_blog_post()
    {
        //Log in
        $this->login();

        $blog = $this->create_blog_post();
        $this->call('GET', 'blog/'.$blog['slug']);
        $this->assertResponseOk();
    }

    /** @test */
    public function can_see_page_2_on_blog_post()
    {
        //Log in
        $this->login();

        $this->create_blog_post();
        $this->call('GET', 'blog?page=2');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_edit_blog_post()
    {
        //Log in
        $this->login();

        //If the blog post doesn't exist, return to the blog frontpage with an error.
        $this->call('GET', 'blog/test9999/edit');
        $this->assertRedirectedTo('blog', array('error' => 'Blog post not found!'));

        //Check we can load the edit page.
        $post = $this->create_blog_post();
        $this->call('GET', '/blog/'.$post['id'].'/edit');
        $this->assertResponseOk();

        //Check we can save changes.
        $title = $this->fake->sentence;
        $blogNew = array(
            'id' => 1,
            'title' => $title,
            'slug' => \Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => $this->user->id
        );
        $this->call('PUT', '/blog/update', $blogNew);
        $this->assertRedirectedTo('blog', array('success' => 'Blog updated successfully'));

        //Check the changes got saved.
        $savedBlog = $this->blogPostRepository->findId(1);

        $this->assertEquals($blogNew['title'], $savedBlog['title']);
        $this->assertEquals($blogNew['slug'], $savedBlog['slug']);
        $this->assertEquals($blogNew['content'], $savedBlog['content']);
    }

    /** @test */
    public function fails_updating_blog_post_with_invalid_data()
    {
        //Log in
        $this->login();

        //create a post
        $post = $this->create_blog_post();

        //Mess things up
        unset($post['title']);

        //Check
        $this->call('PUT', '/blog/'.$post['id'], $post);
        $this->assertRedirectedTo('blog/'.$post['id'].'/edit');
    }

    /** @test */
    public function can_delete_blog_post()
    {
        //Log in
        $this->login();

        //create dummy post.
        $post = $this->create_blog_post();

        //check how many blog posts we have
        $blogPostCount = $this->blogPostRepository->all()->count();

        //It should have returned 1.
        $this->assertEquals($blogPostCount, 1);

        //Delete the post again.
        $this->call('DELETE', '/blog/'.$post['id']);

        //It should be zero now.
        $blogPostCount = $this->blogPostRepository->all()->count();
        $this->assertEquals(0, $blogPostCount);
    }

    /** @test */
    public function can_create_blog_comment()
    {
        //Log in
        $this->login();

        //create dummy post.
        $post = $this->create_blog_post();

        //create dummy post.
        $comment = array(
            'id' => 2,
            'post_id' => $post['id'],
            'user_id' => $this->user->id,
            'content' => $this->fake->text
        );
        $this->call('POST', '/blog/'.$post['id'].'/comment', $comment);

        //check how many blog comments we have
        $blogPostCount = $this->blogPostRepository->all()->count();

        //It should have returned 1.
        $this->assertEquals(1, $blogPostCount);
    }

    /** @test */
    public function can_update_blog_comment()
    {
        //Log in
        $this->login();

        //create dummy post.
        $post = $this->create_blog_post();

        //create dummy comment
        $this->create_blog_comment($post);
        $comment = $this->create_blog_comment($post);

        //Check we can load the edit page.
        $this->call('GET', '/blog/'.$post['id'].'/comment/'.$comment['id'].'/edit');
        $this->assertResponseOk();

        //update the text
        $oldContent = $comment['content'];
        $newContent = $this->fake->text;

        //Just to make sure nothing bad happens later.
        $this->assertNotEquals($oldContent, $newContent);

        $comment['content'] = $newContent;
        $this->call('PUT', '/blog/'.$post['id'].'/comment/'.$comment['id'], $comment);

        $comment = $this->blogCommentRepository->find($comment['id']);
        $this->assertEquals($comment['content'], $newContent);
    }

    /** @test */
    public function can_delete_blog_comment()
    {
        //Log in
        $this->login();

        //create dummy post.
        $post = $this->create_blog_post();

        //create dummy comment
        $comment = $this->create_blog_comment($post);

        //check how many blog comments we have
        $blogCommentCount = $this->blogCommentRepository->findCommentsForPost($post['id'])->count();

        //It should have returned 1.
        $this->assertEquals(1, $blogCommentCount);

        //Now delete the comment again.
        $this->call('DELETE', '/blog/'.$post['id'].'/comment/'.$comment['id']);

        //It should now it should be zero.
        $blogCommentCount = $this->blogCommentRepository->findCommentsForPost($post['id'])->count();
        $this->assertEquals(0, $blogCommentCount);
    }

    protected function create_blog_post()
    {
        $title = $this->fake->sentence;
        $blog = array(
            'id' => 1,
            'title' => $title,
            'slug' => \Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => $this->user->id
        );
        $this->blogPostRepository->create($blog);
        return $blog;
    }

    protected function create_blog_comment($blogPost)
    {
        $comment = array(
            'id' => 1,
            'post_id' => $blogPost['id'],
            'user_id' => $this->user->id,
            'content' => $this->fake->text
        );
        $this->blogCommentRepository->create($comment);
        return $comment;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}


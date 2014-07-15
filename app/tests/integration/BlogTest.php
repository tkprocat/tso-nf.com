<?php

class BlogTest extends TestCase
{

    protected $fake;

    public function setUp()
    {
        parent::setUp();
        Route::enableFilters();
        $this->fake = Faker\Factory::create();
    }

    public function test_create_blog_post_as_guest()
    {
        Route::enableFilters();
        $this->call('GET', 'blog/create');
        $this->assertRedirectedTo('login');
    }

    /** @test */
    public function can_load_frontpage()
    {
        //Check we can load the front page.
        $this->call('GET', '/');

        //Check we get redirected to the blog page.
        $this->assertRedirectedTo('blog');

        //Check we can load the blog page.
        $this->call('GET', '/blog');
        $this->assertResponseOk();
    }

    /** @test */
    public function can_load_frontpage_while_logged_in()
    {
        //Log in
        $this->login();

        //Check we can load the front page.
        $this->call('GET', '/');

        //Check we get redirected to the blog page.
        $this->assertRedirectedTo('blog');
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

        $blog = $this->create_blog_post();
        $this->call('POST', '/blog/store', $blog);

        $this->assertRedirectedToRoute('blog', array(), array('success' => 'Blog posted successfully'));
    }

    /** @test */
    public function fails_creating_blog_post_with_invalid_data()
    {
        //Log in
        $this->login();

        $blog = array(
            'title' => $this->fake->title,
        );
        $this->call('POST', '/blog/store', $blog);

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
    public function can_edit_blog_post()
    {
        //Log in
        $this->login();

        //If the blog post doesn't exist, return to the blog frontpage with an error.
        $this->call('GET', 'blog/test9999/edit');
        $this->assertRedirectedTo('blog', array('error' => 'Blog post not found!'));

        //Check we can load the edit page.
        $blog = $this->create_blog_post();
        $this->call('GET', '/blog/'.$blog['id'].'/edit');
        $this->assertResponseOk();

        //Check we can save changes.
        $title = $this->fake->sentence;
        $blogNew = array(
            'id' => 1,
            'title' => $title,
            'slug' => \Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => Sentry::getUser()->id
        );
        $this->call('PUT', '/blog/update', $blogNew);
        $this->assertRedirectedTo('blog', array('success' => 'Blog updated successfully'));

        //Check the changes got saved.
        $blogRepository = App::make('LootTracker\Blog\BlogPostInterface');
        $savedBlog = $blogRepository->findId(1);

        $this->assertEquals($blogNew['title'], $savedBlog['title']);
        $this->assertEquals($blogNew['slug'], $savedBlog['slug']);
        $this->assertEquals($blogNew['content'], $savedBlog['content']);
    }

    protected function create_blog_post()
    {
        $title = $this->fake->sentence;
        $blog = array(
            'id' => 1,
            'title' => $title,
            'slug' => \Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => Sentry::getUser()->id
        );
        $blog_repository = App::make('LootTracker\Blog\BlogPostInterface');
        $blog_repository->create($blog);
        return $blog;
    }

    protected function login()
    {
        //Log in
        $user = Sentry::findUserByLogin('admin');
        Sentry::login($user);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}


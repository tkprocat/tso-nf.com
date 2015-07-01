<?php

use Illuminate\Support\Str;

class BlogTest extends TestCase
{
    protected $fake;
    protected $blogCommentRepository;
    protected $blogPostRepository;

    public function setUp()
    {
        parent::setUp();
        $this->fake = Faker\Factory::create();
        $this->blogCommentRepository = App::make('LootTracker\Repositories\Blog\BlogCommentInterface');
        $this->blogPostRepository = App::make('LootTracker\Repositories\Blog\BlogPostInterface');
    }

    /** @test */
    public function canLoadCreateBlogPostAsGuest()
    {
        $this->visit('http://localhost/blog/create')
            ->followRedirects('http://localhost/auth/login');
    }

    /** @test */
    public function canLoadCreateNewBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        $this->call('GET', 'blog/create');
        $this->assertResponseOk();
    }

    /** @test */
    public function canNotLoadCreateNewBlogPostWhenNotLoggedIn()
    {
        $this->call('GET', 'blog/create');

        //Check we get redirected to the login page.
        $this->assertRedirectedTo('auth/login');
    }

    /** @test */
    public function canLoadCreateNewBlogComment()
    {
        $this->loginAsAdmin();

        //Post with ID 1 haven't been created so it should redirect to the blog frontpag with an error.
        $test = $this->call('GET', '/blog/1/comment/create');
        $this->assertRedirectedTo('blog', array('error' => 'Blog post not found.'));

        //Create a blog post and try again.
        $this->createBlogPost();
        $this->call('GET', '/blog/1/comment/create');
        $this->assertResponseOk();
    }

    /** @test */
    public function canNotLoadCreateNewBlogCommentWhenNotLoggedIn()
    {
        $this->call('GET', 'blog/1/comment/create');

        //Check we get redirected to the login page.
        $this->assertRedirectedTo('auth/login');
    }

    /** @test */
    public function failToCreateCommentIfBlogDoesNotExists()
    {
        //Log in
        $this->loginAsAdmin();
        //$this->visit('http://localhost/blog/99999999999/comment/create');
        $this->call('GET', '/blog/99999999999/comment/create');
        $this->assertRedirectedTo('/blog');
        $this->assertSessionHas('error', 'Blog post not found.');
    }

    /** @test */
    public function canCreateNewBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        $title = $this->fake->sentence;
        $post = [
            'id' => 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => $this->user->getUser()->id
        ];

        $test = $this->call('POST', '/blog', $post);
        //  dd($test);
        $this->assertRedirectedTo('blog', array(), array('success' => 'Blog posted successfully'));

        //Check that it's saved.
        $blogPostCount = $this->blogPostRepository->all()->count();
        $this->assertEquals(1, $blogPostCount);
    }

    /** @test */
    public function failsCreatingBlogPostWithInvalidData()
    {
        //Log in
        $this->loginAsAdmin();
        $this->visit('http://localhost/blog/create')
            ->type($this->fake->title, 'title')
            ->press('Create')
            ->onPage('http://localhost/blog/create')
            ->assertSessionHasErrors('content');
    }

    /** @test */
    public function canSeeDetailedBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        $blog = $this->createBlogPost();
        $this->call('GET', 'blog/' . $blog['slug']);
        $this->assertResponseOk();
    }

    /** @test */
    public function canSeePage2OnBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        $this->createBlogPost();
        $this->call('GET', 'blog?page=2');
        $this->assertResponseOk();
    }

    /** @test */
    public function canEditBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        //If the blog post doesn't exist, return to the blog frontpage with an error.
        $this->call('GET', 'blog/test9999/edit');
        $this->assertRedirectedTo('blog', array('error' => 'Blog post not found!'));

        //Check we can load the edit page.
        $post = $this->createBlogPost();
        $this->call('GET', '/blog/' . $post['id'] . '/edit');
        $this->assertResponseOk();

        //Check we can save changes.
        $title = $this->fake->sentence;
        $blogNew = array(
            'id' => 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => $this->user->getUser()->id
        );
        $this->call('PUT', '/blog/' . $blogNew['id'], $blogNew);
        $this->assertRedirectedTo('blog', array('success' => 'Blog updated successfully'));

        //Check the changes got saved.
        $savedBlog = $this->blogPostRepository->byId(1);

        $this->assertEquals($blogNew['title'], $savedBlog['title']);
        $this->assertEquals($blogNew['slug'], $savedBlog['slug']);
        $this->assertEquals($blogNew['content'], $savedBlog['content']);
    }

    /** @test */
    public function failsUpdatingBlogPostWithInvalidData()
    {
        //Log in
        $this->loginAsAdmin();

        //create a post
        $post = $this->createBlogPost();


        $url = '/blog/' . $post['id'].'/edit';
        $this->visit($url)
             ->see('Edit blog post');

        //TODO: Fix this!
//        $this->visit($url)
//             ->type('', 'title')
//             ->press('Update')
//             ->seePageIs($url);
    }

    /** @test */
    public function canDeleteBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        //create dummy post.
        $post = $this->createBlogPost();

        //check how many blog posts we have
        $blogPostCount = $this->blogPostRepository->all()->count();

        //It should have returned 1.
        $this->assertEquals($blogPostCount, 1);

        //Delete the post again.
        $this->call('DELETE', '/blog/' . $post['id']);

        //It should be zero now.
        $blogPostCount = $this->blogPostRepository->all()->count();
        $this->assertEquals(0, $blogPostCount);
    }

    /** @test */
    public function canCreateBlogComment()
    {
        //Log in
        $this->loginAsAdmin();

        //create dummy post.
        $post = $this->createBlogPost();

        //create dummy post.
        $comment = array(
            'id' => 2,
            'post_id' => $post['id'],
            'user_id' => $this->user->getUser()->id,
            'content' => $this->fake->text
        );
        $this->call('POST', '/blog/' . $post['id'] . '/comment', $comment);

        //check how many blog comments we have
        $blogPostCount = $this->blogPostRepository->all()->count();

        //It should have returned 1.
        $this->assertEquals(1, $blogPostCount);
    }

    /** @test */
    public function canUpdateBlogComment()
    {
        //Log in
        $this->loginAsAdmin();

        //create dummy post.
        $post = $this->createBlogPost();

        //create dummy comment
        $this->createBlogComment($post);
        $comment = $this->createBlogComment($post);

        //Check we can load the edit page.
        $this->call('GET', '/blog/comment/' . $comment['id'] . '/edit');
        $this->assertResponseOk();

        //update the text
        $oldContent = $comment['content'];
        $newContent = $this->fake->text;

        //Just to make sure nothing bad happens later.
        $this->assertNotEquals($oldContent, $newContent);

        $comment['content'] = $newContent;
        $this->call('PUT', '/blog/comment/' . $comment['id'], $comment);

        $comment = $this->blogCommentRepository->byId($comment['id']);
        $this->assertEquals($comment['content'], $newContent);
    }

    /** @test */
    public function canDeleteBlogComment()
    {
        //Log in
        $this->loginAsAdmin();

        //create dummy post.
        $post = $this->createBlogPost();

        //create dummy comment
        $comment = $this->createBlogComment($post);

        //check how many blog comments we have
        $blogCommentCount = $this->blogCommentRepository->findCommentsForPost($post['id'])->count();

        //It should have returned 1.
        $this->assertEquals(1, $blogCommentCount);

        //Now delete the comment again.
        $this->call('DELETE', '/blog/comment/' . $comment['id']);

        //It should now it should be zero.
        $blogCommentCount = $this->blogPostRepository->byId($post['id'])->comments()->count();
        $this->assertEquals(0, $blogCommentCount);
    }

    protected function createBlogPost()
    {
        $title = $this->fake->sentence;
        $blog = array(
            'id' => 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->fake->text,
            'user_id' => $this->user->getUser()->id
        );
        $this->blogPostRepository->create($blog);

        return $blog;
    }

    protected function createBlogComment($blogPost)
    {
        $comment = array(
            'id' => 1,
            'post_id' => $blogPost['id'],
            'user_id' => $this->user->getUser()->id,
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


<?php namespace LootTracker\Test\Functional;

use App;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use LootTracker\Repositories\Blog\BlogCommentInterface;
use LootTracker\Repositories\Blog\BlogPostInterface;
use LootTracker\Test\TestCase;

class BlogTest extends TestCase
{

    /**
     * @var $faker \Faker\Factory
     */
    protected $faker;

    /**
     * @var $blogCommentRepo \LootTracker\Repositories\Blog\BlogCommentInterface
     */
    protected $blogCommentRepo;

    /**
     * @var $blogPostRepo \LootTracker\Repositories\Blog\BlogPostInterface
     */
    protected $blogPostRepo;

    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->blogCommentRepo = App::make(BlogCommentInterface::class);
        $this->blogPostRepo = App::make(BlogPostInterface::class);
    }

    /** @test */
    public function canLoadFrontPage()
    {
        $this->visit('blog');

        //Log in
        $this->login();
        $this->visit('blog')
            ->see('Create new blog post', true);

        //Log in
        $this->loginAsAdmin();
        $this->visit('blog')
            ->see('Create new blog post');
    }

    /** @test */
    public function canLoadCreateBlogPostAsGuest()
    {
        $this->visit('blog/create')
            ->followRedirects()
            ->seePageIs('auth/login');
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

        //Post with ID 2 haven't been created so it should redirect to the blog frontpag with an error.
        $this->visit('/blog/2/comment/create')
            ->seePageIs('blog')
            ->see('Blog post not found.');

        //Create a blog post and try again.
        $this->createBlogPost();
        $this->visit('/blog/1/comment/create')
            ->see('Post a comment');
    }

    /** @test */
    public function canNotLoadCreateNewBlogCommentWhenNotLoggedIn()
    {
        //Check we get redirected to the login page.
        $this->visit('blog/1/comment/create')
            ->seePageIs('auth/login');
    }

    /** @test */
    public function failToCreateCommentIfBlogDoesNotExists()
    {
        //Log in
        $this->loginAsAdmin();
        $this->visit('blog/99999999999/comment/create')
            ->seePageIs('blog')
            ->see('Blog post not found.');
    }

    /** @test */
    public function canCreateNewBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        $title = $this->faker->sentence;
        $post = [
            'id' => 2,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->text,
            'user_id' => $this->userRepo->getUser()->id
        ];

        $this->visit('blog')
            ->click('Create new blog post')
            ->type($post['title'], 'title')
            ->type($post['content'], 'content')
            ->press('Create')
            ->seePageIs('blog')
            ->see('Post created successfully')
            ->seeInDatabase('posts', ['id' => $post['id'], 'title' => $post['title'],
                                      'slug' => $post['slug'], 'content' => $post['content']]);
    }

    /** @test */
    public function failsCreatingBlogPostWithInvalidData()
    {
        //Log in
        $this->loginAsAdmin();
        $this->visit('/blog/create')
            ->type($this->faker->title, 'title')
            ->press('Create')
            ->followRedirects()
            ->onPage('blog/create')
            ->see('The content field is required.');
    }

    /** @test */
    public function canSeeDetailedBlogPost()
    {
        $blog = $this->blogPostRepo->byId(1);
        $this->visit('blog/' . $blog['slug'])
            ->see($blog['title'])
            ->see($blog['content']);
    }

    /** @test */
    public function canSeePage2OnBlogPost()
    {
        $this->visit('blog?page=2');
    }

    /** @test */
    public function canEditBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        //If the blog post doesn't exist, return to the blog frontpage with an error.
        $this->visit('blog/test9999/edit')
            ->seePageIs('blog')
            ->see('Post not found!');

        //Check we can load the edit page.
        $post = $this->createBlogPost();
        $this->visit('blog/' . $post['id'] . '/edit')
            ->see('Edit blog');

        //Check we can save changes.
        $title = $this->faker->sentence;
        $updatedPost = array(
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->text,
            'user_id' => $this->userRepo->getUser()->id
        );
        $this->visit('blog/' . $post['id'] . '/edit')
            ->type($updatedPost['title'], 'title')
            ->type($updatedPost['content'], 'content')
            ->press('Update')
            ->seePageIs('blog')
            ->see('Post updated successfully')
            ->seeInDatabase('posts', ['id' => $post['id'],
                'title' => $updatedPost['title'],
                'slug' => $updatedPost['slug'],
                'content' => $updatedPost['content']]);
    }

    /** @test */
    public function failsUpdatingBlogPostWithInvalidData()
    {
        //Log in
        $this->loginAsAdmin();

        //create a post
        $post = $this->createBlogPost();


        $url = '/blog/' . $post['id'] . '/edit';
        $this->visit($url)
            ->see('Edit blog post')
            ->type('', 'title')
            ->press('Update')
            ->seePageIs($url);
    }

    /** @test */
    public function canDeleteBlogPost()
    {
        //Log in
        $this->loginAsAdmin();

        //create dummy post.
        $post = $this->createBlogPost();

        //check how many blog posts we have
        $blogPostCount = $this->blogPostRepo->all()->count();

        //It should have returned 2.
        $this->assertEquals($blogPostCount, 2);

        //Delete the post again.
        $this->call('DELETE', '/blog/' . $post['id']);

        //It should be one now.
        $blogPostCount = $this->blogPostRepo->all()->count();
        $this->assertEquals(1, $blogPostCount);
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
            'user_id' => $this->userRepo->getUser()->id,
            'content' => $this->faker->text
        );
        $this->call('POST', '/blog/' . $post['id'] . '/comment', $comment);

        //check how many blog comments we have
        $blogPostCount = $this->blogPostRepo->all()->count();

        //It should have returned 2.
        $this->assertEquals(2, $blogPostCount);
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
        $newContent = $this->faker->text;

        //Just to make sure nothing bad happens later.
        $this->assertNotEquals($oldContent, $newContent);

        $comment['content'] = $newContent;
        $this->call('PUT', '/blog/comment/' . $comment['id'], $comment);

        $comment = $this->blogCommentRepo->byId($comment['id']);
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
        $blogCommentCount = $this->blogCommentRepo->findCommentsForPost($post['id'])->count();

        //It should have returned 1.
        $this->assertEquals(1, $blogCommentCount);

        //Now delete the comment again.
        $this->call('DELETE', '/blog/comment/' . $comment['id']);

        //It should now it should be zero.
        $blogCommentCount = $this->blogPostRepo->byId($post['id'])->comments()->count();
        $this->assertEquals(0, $blogCommentCount);
    }

    protected function createBlogPost()
    {
        $title = $this->faker->sentence;
        $blog = [
            'id' => 2,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->text,
            'user_id' => 1
        ];
        $this->blogPostRepo->create($blog);

        return $blog;
    }

    protected function createBlogComment($blogPost)
    {
        $comment = array(
            'id' => 2,
            'post_id' => $blogPost['id'],
            'user_id' => $this->userRepo->getUser()->id,
            'content' => $this->faker->text
        );
        $this->blogCommentRepo->create($comment);

        return $comment;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}

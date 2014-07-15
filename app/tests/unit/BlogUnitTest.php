<?php
use Mockery as m;
class BlogUnitTest extends TestCase {

    public function setUp() {
        parent::setUp();
    }

    /** @test */
    public function fail_to_create_comment_if_blog_does_not_exists()
    {
        $this->call('GET', 'blog/99999999999/comment/create');

        $this->assertRedirectedToRoute('blog', array(), array('error' => 'Blog post not found.'));
    }

     public function tearDown()
    {
        m::close();
        parent::tearDown();
    }
}


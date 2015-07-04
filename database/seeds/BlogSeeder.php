<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use LootTracker\Repositories\Blog\BlogComment;
use LootTracker\Repositories\Blog\BlogPost;
use LootTracker\Repositories\User\User;

class BlogSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $admin = User::whereUsername('admin')->firstOrFail();
        $user1 = User::whereUsername('user1')->firstOrFail();

        $post = BlogPost::create([
            'title' => 'Hello world',
            'slug' => Str::slug('Hello world'),
            'content' => 'Welcome to tso-nf.com my friend, enjoy your stay',
            'user_id' => $admin->id
        ]);

        BlogComment::create([
           'post_id' => $post->id,
           'content' => 'I will!',
           'user_id' => $user1->id
        ]);
	}
}
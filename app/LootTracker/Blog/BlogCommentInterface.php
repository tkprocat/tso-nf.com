<?php

namespace LootTracker\Blog;

interface BlogCommentInterface
{
    public function findCommentsForPost($blogPostId);

    public function saveBlogComment($data);

    public function updateBlogComment($id, $data);

    public function find($id);
}
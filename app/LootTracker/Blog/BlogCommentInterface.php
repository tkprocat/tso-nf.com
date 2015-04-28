<?php

namespace LootTracker\Blog;

interface BlogCommentInterface
{
    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function findCommentsForPost($blogPostId);

    public function find($id);
}
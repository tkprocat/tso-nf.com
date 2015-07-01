<?php

namespace LootTracker\Repositories\Blog;

interface BlogCommentInterface
{
    public function byId($id);

    public function create($data);

    public function delete($id);

    public function findCommentsForPost($blogPostId);

    public function update($id, $data);
}
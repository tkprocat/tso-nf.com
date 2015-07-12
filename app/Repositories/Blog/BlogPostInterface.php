<?php namespace LootTracker\Repositories\Blog;

interface BlogPostInterface
{
    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function all();

    public function findSlug($slug);

    public function byId($id);

    public function findComments($id);
}

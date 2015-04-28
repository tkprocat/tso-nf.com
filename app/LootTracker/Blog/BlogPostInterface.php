<?php
namespace LootTracker\Blog;

interface BlogPostInterface {
    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function all();

    public function findPage($page, $limit);

    public function findSlug($slug);

    public function findId($id);

    public function findComments($id);
} 
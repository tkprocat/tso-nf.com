<?php
namespace LootTracker\Blog;

interface BlogPostInterface {
    public function all();

    public function create($input);

    public function findPage($page, $limit);

    public function findSlug($slug);

    public function delete($id);

    public function saveBlogPost($data);

    public function findId($id);

    public function findComments($id);
} 
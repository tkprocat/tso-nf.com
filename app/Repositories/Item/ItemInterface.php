<?php namespace LootTracker\Repositories\Item;

interface ItemInterface
{
    public function all();

    public function byId($id);

    public function byName($name);
}

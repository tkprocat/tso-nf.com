<?php namespace LootTracker\Repositories\Item\Admin;

interface AdminItemInterface
{
    public function create($data);

    public function delete($item_id);

    public function update($item_id, $data);
}

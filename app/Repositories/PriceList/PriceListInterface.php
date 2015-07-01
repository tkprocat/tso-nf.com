<?php
namespace LootTracker\Repositories\PriceList;


interface PriceListInterface
{
    public function getAllItems();

    public function byId($id);
}
<?php
namespace LootTracker\PriceList;


interface PriceListInterface {
    public function getAllItems();
    public function findItemById($id);
}
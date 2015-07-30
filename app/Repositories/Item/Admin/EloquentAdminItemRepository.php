<?php namespace LootTracker\Repositories\Item\Admin;

use LootTracker\Repositories\Item\Item;
use LootTracker\Repositories\Price\Admin\AdminPriceInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class EloquentAdminItemRepository
 * @package LootTracker\Repositories\Item\Admin
 */
class EloquentAdminItemRepository implements AdminItemInterface
{

    /**
     * @var AdminPriceInterface
     */
    protected $adminPriceRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;


    /**
     * EloquentAdminItemRepository constructor.
     *
     * @param AdminPriceInterface $adminPriceInterface
     * @param UserInterface       $user
     */
    public function __construct(AdminPriceInterface $adminPriceInterface, UserInterface $user)
    {
        $this->adminPriceRepo = $adminPriceInterface;
        $this->userRepo = $user;
    }


    /**
     * @param $data
     *
     * @return \LootTracker\Repositories\Item\Item
     */
    public function create($data)
    {
        $item = new Item();
        $item->name = e($data['name']);
        $item->category = e($data['category']);
        $item->save();

        //Set a default price for the item on creation.
        $min_price = (isset($data['min_price']) ? $data['min_price'] : 0);
        $avg_price = (isset($data['avg_price']) ? $data['avg_price'] : 0);
        $max_price = (isset($data['max_price']) ? $data['max_price'] : 0);
        $user_id = (isset($data['user_id']) ? $data['user_id'] : $this->userRepo->getUser()->id);
        $this->adminPriceRepo->update($item->id, $min_price, $avg_price, $max_price, $user_id);

        return $item;
    }


    /**
     * @param $item_id
     */
    public function delete($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->delete();
    }


    /**
     * @param $item_id
     * @param $data
     */
    public function update($item_id, $data)
    {
        $item = Item::findOrFail($item_id);
        $item->name = e($data['name']);
        $item->category = e($data['category']);
        $item->save();
    }
}
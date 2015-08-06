<?php namespace LootTracker\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Requests\UpdatePriceRequest;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Repositories\Price\Admin\AdminPriceInterface;
use LootTracker\Repositories\Price\PriceInterface;
use LootTracker\Repositories\User\UserInterface;

class AdminPriceController extends Controller
{

    /**
     * @var AdminPriceInterface
     */
    protected $adminPriceRepo;

    /**
     * @var ItemInterface
     */
    protected $itemRepo;

    /**
     * @var UserInterface
     */
    protected $userRepo;

    /**
     * @var PriceInterface
     */
    protected $priceRepo;


    /**
     * @param AdminPriceInterface $adminPrice
     * @param PriceInterface      $price
     * @param ItemInterface       $item
     * @param UserInterface       $user
     */
    public function __construct(AdminPriceInterface $adminPrice, PriceInterface $price, ItemInterface $item, UserInterface $user)
    {
        $this->adminPriceRepo = $adminPrice;
        $this->itemRepo = $item;
        $this->priceRepo = $price;
        $this->userRepo = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $items = $this->itemRepo->all();

        return view('admin.prices.index')->with('items', $items);
    }


    /**
     * Display the specified resource.
     *
     * @param $item_id
     * @return \Illuminate\View\View
     */
    public function show($item_id)
    {
        $priceItem = $this->itemRepo->byId($item_id);
        $priceHistory = $this->priceRepo->findAllPriceChangesForItemById($item_id);

        return view('admin.prices.show')->with(array('item' => $priceItem, 'price_history' => $priceHistory));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $item_id
     * @return \Illuminate\View\View
     */
    public function edit($item_id)
    {
        $priceItem = $this->itemRepo->byId($item_id);
        if (is_null($priceItem)) {
            return Redirect::to('admin/prices')->with('error', 'Item not found!');
        }

        return view('admin.prices.edit')->with('item', $priceItem);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePriceRequest $request
     * @param int              $item_id
     *
     * @return \Illuminate\View\View
     */
    public function update(UpdatePriceRequest $request, $item_id)
    {
        $data = $request->all();
        $user_id = $this->userRepo->getUser()->id;
        $this->adminPriceRepo->update($item_id, $data['min_price'], $data['avg_price'], $data['max_price'], $user_id);
        return Redirect::to('admin/prices')->with('success', 'Prices updated.');
    }
}

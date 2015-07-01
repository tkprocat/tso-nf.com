<?php namespace LootTracker\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LootTracker\Http\Requests\PriceItemRequest;
use LootTracker\Http\Requests\UpdatePriceRequest;
use LootTracker\Repositories\PriceList\Admin\AdminPriceListInterface;
use LootTracker\Repositories\User\UserInterface;

class AdminPriceListController extends Controller
{
    protected $adminPriceListRepo;
    protected $userRepo;

    public function __construct(AdminPriceListInterface $adminPrice, UserInterface $user)
    {
        $this->adminPriceListRepo = $adminPrice;
        $this->userRepo = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->adminPriceListRepo->all();

        return view('admin.prices.index')->with('items', $items);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.prices.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param PriceItemRequest $request
     * @return Response
     */
    public function store(PriceItemRequest $request)
    {
        $this->adminPriceListRepo->create($request->all());
        return Redirect::to('admin/prices')->with('success', 'Item added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $priceItem = $this->adminPriceListRepo->byId($id);
        $priceHistory = $this->adminPriceListRepo->findAllPriceChangesForItemById($id);

        return view('admin.prices.show')->with(array('item' => $priceItem, 'price_history' => $priceHistory));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $priceItem = $this->adminPriceListRepo->byId($id);
        if (is_null($priceItem)) {
            return Redirect::to('admin/prices')->with('error', 'Item not found!');
        }

        return view('admin.prices.edit')->with('item', $priceItem);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param PriceItemRequest $request
     * @return Response
     */
    public function update(PriceItemRequest $request, $id)
    {
        $data = $request->all();
        $this->adminPriceListRepo->update($id, $data);
        return Redirect::to('admin/prices')->with('success', 'Item updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->adminPriceListRepo->deleteItem($id);
    }


    //Item price management below

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createNewPrice($id)
    {
        $item = $this->adminPriceListRepo->byId($id);
        return view('admin.prices.createNewPrice')->with(array('item' => $item));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param UpdatePriceRequest $request
     * @return Response
     */
    public function storeNewPrice(UpdatePriceRequest $request, $item_id)
    {
        $data = $request->all();
        $this->adminPriceListRepo->updatePriceForItem($item_id, $data['min_price'], $data['avg_price'], $data['max_price']);
        return Redirect::to('admin/prices')->with('success', 'New price registered');
    }
}

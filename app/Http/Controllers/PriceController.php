<?php namespace LootTracker\Http\Controllers;

use Cache;
use LootTracker\Repositories\Item\ItemInterface;
use LootTracker\Repositories\Price\PriceInterface;
use Response;

class PriceController extends Controller
{

    /**
     * @var ItemInterface
     */
    protected $itemRepo;

    /**
     * @var PriceInterface
     */
    protected $priceRepo;


    /**
     * @param ItemInterface  $items
     * @param PriceInterface $prices
     */
    public function __construct(ItemInterface $items, PriceInterface $prices)
    {
        $this->itemRepo = $items;
        $this->priceRepo = $prices;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $items = $this->itemRepo->all();

        return view('prices.index')->with('items', $items);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\View\View
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  string $itemName
     *
     * @return \Illuminate\View\View
     */
    public function show($itemName)
    {
        $item = $this->itemRepo->byName(urldecode($itemName));
        $priceHistory = $this->priceRepo->findAllPriceChangesForItemById($item->id);
        return view('prices.show')->with(array('item' => $item, 'priceHistory' => $priceHistory));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $item_id
     *
     * @return \Illuminate\View\View
     */
    public function edit($item_id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $item_id
     *
     * @return \Illuminate\View\View
     */
    public function update($item_id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $item_id
     *
     * @return \Illuminate\View\View
     */
    public function destroy($item_id)
    {
        //
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showAdvancedCalc()
    {
        return view('prices.advancedCalc');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showSimpleCalc()
    {
        return view('prices.simpleCalc');
    }


    /**
     * @return Response
     */
    public function getItemsWithPrices()
    {
        $items = Cache::tags('prices')->remember('getItemsWithPrices', 10, function(){
            return $this->itemRepo->getItemsWithPrices();
        });

        return Response::json($items);
    }
}

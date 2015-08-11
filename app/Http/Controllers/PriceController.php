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
        $items = Cache::tags('prices', 'items')->remember('getItemsWithPrices', 10, function(){
            return $this->itemRepo->getItemsWithPrices();
        });

        return Response::json($items);
    }
}

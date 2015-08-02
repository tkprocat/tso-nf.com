<?php namespace LootTracker\Http\Controllers;

use LootTracker\Repositories\Item\ItemInterface;
use Response;

class PriceController extends Controller
{

    /**
     * @var ItemInterface
     */
    protected $itemRepo;


    /**
     * @param ItemInterface $itemRepo
     */
    public function __construct(ItemInterface $itemRepo)
    {
        $this->itemRepo = $itemRepo;
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
     * @param  int $item_id
     *
     * @return \Illuminate\View\View
     */
    public function show($item_id)
    {
        //
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
        $items = $this->itemRepo->getItemsWithPrices();
        return Response::json($items);
    }
}

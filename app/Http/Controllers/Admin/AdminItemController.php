<?php namespace LootTracker\Http\Controllers;


use Redirect;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use LootTracker\Http\Requests\ItemRequest;
use LootTracker\Repositories\Item\Admin\ItemUsedInAdventureException;
use LootTracker\Repositories\Item\Admin\AdminItemInterface;
use LootTracker\Repositories\Item\ItemInterface;

/**
 * Class AdminItemController
 * @package LootTracker\Http\Controllers
 */
class AdminItemController extends Controller
{

    /**
     * @var AdminItemInterface
     */
    protected $adminItemRepo;

    /**
     * @var ItemInterface
     */
    protected $itemRepo;


    /**
     * @param AdminItemInterface $adminPrice
     * @param ItemInterface      $itemRepo
     */
    public function __construct(AdminItemInterface $adminPrice, ItemInterface $itemRepo)
    {
        $this->adminItemRepo = $adminPrice;
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

        return view('admin.items.index')->with('items', $items);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.items.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ItemRequest $request
     * @return \Illuminate\View\View
     */
    public function store(ItemRequest $request)
    {
        $this->adminItemRepo->create($request->all());
        return Redirect::to('admin/items')->with('success', 'Item added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param $item_id
     * @return \Illuminate\View\View
     */
    public function show($item_id)
    {
        try
        {
            $item = $this->itemRepo->byId($item_id);
            return view('admin.items.show')->with(array('item' => $item));
        } catch(ModelNotFoundException $ex) {
            return Redirect::to('admin/items')->withErrors('Item not found!');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $item_id
     * @return \Illuminate\View\View
     */
    public function edit($item_id)
    {
        try
        {
            $item = $this->itemRepo->byId($item_id);
        } catch(ModelNotFoundException $ex) {
            return Redirect::to('admin/items')->withErrors('Item not found!');
        }

        return view('admin.items.edit')->with('item', $item);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ItemRequest $request
     * @param int         $item_id
     *
     * @return \Illuminate\View\View
     */
    public function update(ItemRequest $request, $item_id)
    {
        $this->adminItemRepo->update($item_id, $request->all());
        return Redirect::to('admin/items')->with('success', 'Item updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int    $item_id
     *
     * @return Redirect
     */
    public function destroy($item_id)
    {
        try {
            $this->adminItemRepo->delete($item_id);
        } catch(ItemUsedInAdventureException $ex) {
            return redirect()->back()->withErrors('Item is used on an adventure and can not be deleted.');
        }
        return redirect()->back();
    }
}

<?php
use LootTracker\PriceList\Admin\AdminPriceListInterface;
use \Authority\Repo\User\UserInterface;

class AdminPriceListController extends \BaseController
{

    protected $adminPriceList;
    protected $user;

    function __construct(AdminPriceListInterface $adminPrice, UserInterface $user)
    {
        $this->adminPriceList = $adminPrice;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->adminPriceList->getAllItems();
        return View::make('admin.prices.index')->with('items', $items);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.prices.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //Check if the user has permission to post news.
        $user =  Sentry::getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $data = Input::all();

        $data['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->adminPriceList->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->adminPriceList->addItem($data);
            return Redirect::to('admin/prices')->with('success', 'Item added successfully');
        } else {
            //Failed validation
            return Redirect::to('admin/prices/create')->withInput()->withErrors($this->adminPriceList->validator->errors());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $priceItem = $this->adminPriceList->findPriceById($id);
        $priceHistory = $this->adminPriceList->getAllPriceChangesForItemById($id);
        return View::make('admin.prices.show')->with(array('item' => $priceItem, 'price_history' => $priceHistory));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $priceItem = $this->adminPriceList->findPriceById($id);
        if (is_null($priceItem))
            return Redirect::to('admin/prices')->with('error', 'Item not found!');
        return View::make('admin.prices.edit')->with('item', $priceItem);
    }


    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        //Check if the user has permission to post news.
        $user =  Sentry::getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $price = Input::all();
        $price['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->adminPriceList->validator->with($price)->passes()) {
            //Passed validation, make the update.
            $this->adminPriceList->updateItem($price['item_id'], $price);
            return Redirect::to('admin/prices')->with('success', 'Item updated successfully');
        } else {
            //Failed validation
            return Redirect::back()->withErrors($this->adminPriceList->validator->errors())->withInput($price);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->adminPriceList->deleteItem($id);
    }


    //Item price management below

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createNewPrice($id)
    {
        $item = $this->adminPriceList->findPriceById($id);
        return View::make('admin.prices.createNewPrice')->with(array('item' => $item));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storeNewPrice()
    {
        //Check if the user has permission to post news.
        $user =  Sentry::getUser();
        if (!$user->hasAccess('admin'))
            return Redirect::to('login');

        $data = Input::all();

        $data['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->adminPriceList->validatorNewPrice->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->adminPriceList->updatePriceForItem($data['item_id'], $data['min_price'], $data['avg_price'], $data['max_price']);
            return Redirect::to('admin/prices')->with('success', 'New price registered');
        } else {
            //Failed validation
            return Redirect::to('admin/prices/create')->withInput()->withErrors($this->adminPriceList->validatorNewPrice->errors());
        }
    }
}

<?php
use LootTracker\Adventure\Admin\AdminAdventureInterface;
use \Authority\Repo\User\UserInterface;

class AdminAdventureController extends \BaseController
{

    protected $adminAdventure;
    protected $user;

    function __construct(AdminAdventureInterface $adminAdventure, UserInterface $user)
    {
        $this->adminAdventure = $adminAdventure;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $adventures = $this->adminAdventure->findAllAdventures();
        return View::make('admin.adventure.index')->with('adventures', $adventures);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.adventure.create');
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

        //Removes any "empty" items.
        $itemCount = count($data["items"]);
        if ($itemCount > 0) {
            for($a = 1;$a <= $itemCount; $a++) {
                if (($data["items"][$a]["slot"] == "") && ($data["items"][$a]["type"] =="") && ($data["items"][$a]["amount"] =="")) {
                    unset($data["items"][$a]);
                }
            }
        }

        $this->adminAdventure->validator->updateRules($data);
        $data['user_id'] = Sentry::getUser()->id; //This feels wrong....

        if ($this->adminAdventure->validator->with($data)->passes()) {
            //Passed validation, store the blog post.
            $this->adminAdventure->create($data);
            return Redirect::to('admin/adventures')->with('success', 'Adventure added successfully');
        } else {
            //Failed validation
            return Redirect::to('admin/adventures/create')->withInput()->withErrors($this->adminAdventure->validator->errors());
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
        $adventure = $this->adminAdventure->findAdventureById($id);
        return View::make('admin.adventure.show')->with(array('adventure' => $adventure));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $adventure = $this->adminAdventure->findAdventureById($id);
        if (is_null($adventure))
            return Redirect::to('admin/adventures')->with('error', 'Adventure not found!');
        return View::make('admin.adventure.edit')->with('adventure', $adventure);
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

        $adventure = Input::all();
        $adventure['user_id'] = Sentry::getUser()->id; //This feels wrong....

        //Removes any "empty" items.
        $itemCount = count($adventure["items"]);
        if ($itemCount > 0) {
            for($a = 1;$a <= $itemCount; $a++) {
                if (($adventure["items"][$a]["slot"] == "") && ($adventure["items"][$a]["type"] =="") && ($adventure["items"][$a]["amount"] =="")) {
                    unset($adventure["items"][$a]);
                }
            }
        }

        if ($this->adminAdventure->validator->with($adventure)->passes()) {
            //Passed validation, make the update.
            $this->adminAdventure->update($adventure['adventure_id'], $adventure);
            return Redirect::to('admin/adventures')->with('success', 'Adventure updated successfully');
        } else {
            //Failed validation
            return Redirect::back()->withErrors($this->adminAdventure->validator->errors())->withInput($adventure);
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
        //
    }

    public function getItemTypes()
    {
        //TODO: Move to repo.
        return DB::table('adventure_loot')->groupBy('type')->orderBy('type')->lists('type');
    }

    public function getAdventureTypes()
    {
        //TODO: Move to repo.
        return DB::table('adventure')->groupBy('type')->orderBy('type')->lists('type');
    }
}

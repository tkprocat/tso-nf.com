<?php
use LootTracker\Adventure\Admin\AdminAdventureInterface;
use \Authority\Repo\User\UserInterface;

class AdminController extends \BaseController {

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
		$userCount = count($this->user->all());
		$registeredAdventures = $this->adminAdventure->findAllAdventures()->count();
		return View::make('admin.index', compact('userCount', 'registeredAdventures'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
}

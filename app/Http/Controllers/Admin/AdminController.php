<?php namespace LootTracker\Http\Controllers;

use LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface;
use LootTracker\Repositories\User\UserInterface;

/**
 * Class AdminController
 * @package LootTracker\Http\Controllers
 */
class AdminController extends Controller
{

    /**
     * @var AdminAdventureInterface
     */
    protected $adminAdventure;

    /**
     * @var UserInterface
     */
    protected $user;


    /**
     * @param AdminAdventureInterface $adminAdventure
     * @param UserInterface           $user
     */
    public function __construct(AdminAdventureInterface $adminAdventure, UserInterface $user)
    {
        $this->adminAdventure = $adminAdventure;
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userCount = count($this->user->all());
        $registeredAdventures = $this->adminAdventure->all()->count();

        return view('admin.index', compact('userCount', 'registeredAdventures'));
    }
}

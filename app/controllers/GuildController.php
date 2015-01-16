<?php

use LootTracker\Guild\GuildInterface;
use Illuminate\Support\MessageBag as MessageBag;

class GuildController extends BaseController
{

    protected $guild;
    protected $user;
    protected $sentry;

    public function __construct(GuildInterface $guild, \Authority\Repo\User\UserInterface $user,
                                Cartalyst\Sentry\Sentry $sentry)
    {
        $this->guild = $guild;
        $this->user = $user;
        $this->sentry = $sentry;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page = Input::get('page', 1);
        $guildsPerPage = 10;
        $pagiData = $this->guild->findPage($page, $guildsPerPage);

        $guilds = Paginator::make(
            $pagiData->items,
            $pagiData->totalItems,
            $guildsPerPage
        );

        return View::make('guilds.index')->with('guilds', $guilds);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('guilds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!Sentry::check())
            return Redirect::to('login');

        $data = Input::all();
        $user_id = Sentry::getUser()->id;
        if ($this->guild->validator->with($data)->passes()) {
            //Passed validation, store the guild.
            $this->guild->create($data, $user_id);
            return Redirect::to('guilds')->with('success', 'Guild created successfully');
        } else {
            //Failed validation
            return Redirect::back()->withInput()->withErrors($this->guild->validator->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $guild = $this->guild->findId($id);
        $admins = $this->guild->getAdmins($id);
        $members = $this->guild->getMembers($id);
        return View::make('guilds.show', compact('guild', 'admins', 'members', 'guild_access_admins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $guild = $this->guild->findId($id);

        if ((Sentry::hasAccess('admin')) || (Sentry::inGroup(Sentry::findGroupByName('Guild_'.$guild->tag.'_Admins')))) {
            return View::make('guilds.edit')->with('guild', $guild);
        } else {
            Session::flash('error', 'Sorry, you do not have the necessary permissions to edit this guild.');
            return Redirect::to('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $user = Sentry::getUser();
        $data = Input::all();
        $guild = $this->guild->findId($id);

        //Check if the user is admin or fail
        if (($user != null) && ((Sentry::hasAccess('admin')) || (Sentry::inGroup(Sentry::findGroupByName('Guild_'.$guild->tag.'_Admins'))))) {
            if (!is_numeric($id)) {
                App::abort(404);
            }

            if ($this->guild->validator->with($data)->passes()) {
                $this->guild->update($data);

                // Success!
                Session::flash('success', 'Guild "' . Input::get('name') . '" updated.');
                return Redirect::to('guilds');
            } else {
                Session::flash('error', 'Error updating the guild information.');
                return Redirect::action('UserController@edit', array($id))->withInput()->withErrors($this->guild->validator->errors());
            }
        } else {
            return Redirect::route('login');
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
        $user = Sentry::getUser();
        $guild = $this->guild->findId($id);

        //Check if the user is admin or fail
        if (($user != null) && ((Sentry::hasAccess('admin')) || (Sentry::inGroup(Sentry::findGroupByName('Guild_'.$guild->tag.'_Admins'))))) {
            if (!is_numeric($id)) {
                App::abort(404);
            }

            $guild = $this->guild->findId($id);
            if (($guild != null) && ($guild->delete())) {
                Session::flash('success', 'Guild deleted.');
                return Redirect::to('/guilds');
            } else {
                Session::flash('error', 'Unable to delete guild.');
                return Redirect::to('/guilds');
            }
        } else {
            return Redirect::route('login');
        }
    }

    public function addMember()
    {
        $user = null;
        try {
            $user = $this->sentry->findUserByLogin(Input::get('username'));
        } catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
            Session::put('error', 'User does not exists.');
            return Redirect::back();
        }

        //Get the guild or return an error.
        $guild_id = Input::get('guild_id');
        $guild = $this->guild->findId($guild_id);
        if ($guild == null)
            return Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!(Sentry::hasAccess('admin') || Sentry::inGroup(Sentry::findGroupByName('Guild_'.$guild->tag.'_Admins'))))
            return Redirect::back()->withErrors(array('username' => 'You do not have sufficient permissions.'));

        $this->guild->addMember($guild_id, $user->id);
        return Redirect::back();
    }

    public function removeMember()
    {
        $user = $this->sentry->findUserByLogin(Input::get('username'));
        if (!isset($user)) {
            $errors = new MessageBag();
            $errors->add('username', 'User not found.');
            return Redirect::back()->withErrors($errors);
        }

        //Get the guild or return an error.
        $guild_id = Input::get('guild_id');
        $guild = $this->guild->findId($guild_id);
        if ($guild == null)
            return Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!(Sentry::hasAccess('admin') || Sentry::inGroup(Sentry::findGroupByName('Guild_'.$guild->tag.'_Admins'))))
            return Redirect::back()->withErrors('You do not have sufficient permissions.');

        $this->guild->removeMember($guild_id, $user->id);
        return Redirect::back();
    }


    public function removeMemberById($guild_id, $user_id)
    {
        $user = $this->sentry->findUserById($user_id);
        if (!isset($user)) {
            $errors = new MessageBag();
            $errors->add('username', 'User not found.');
            return Redirect::back()->withErrors($errors);
        }

        //Get the guild or return an error.
        $guild = $this->guild->findId($guild_id);
        if ($guild == null)
            return Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!(Sentry::hasAccess('admin') || Sentry::inGroup(Sentry::findGroupByName('Guild_'.$guild->tag.'_Admins'))))
            return Redirect::back()->withErrors('You do not have sufficient permissions.');

        $this->guild->removeMember($guild_id, $user->id);
        return Redirect::back();
    }
    public function promoteMemberByTag($guild_tag, $user_id)
    {
        $guild = $this->guild->findTag($guild_tag);
        $this->promoteMember($guild->id, $user_id);
    }

    public function promoteMember($guild_id, $user_id)
    {
        $this->guild->promoteMember($guild_id, $user_id);
        return Redirect::back();
    }

    public function demoteMemberByTag($guild_tag, $user_id)
    {
        $guild = $this->guild->findTag($guild_tag);
        $this->demoteMember($guild->id, $user_id);
    }

    public function demoteMember($guild_id, $user_id)
    {
        $this->guild->demoteMember($guild_id, $user_id);
        return Redirect::back();
    }
}

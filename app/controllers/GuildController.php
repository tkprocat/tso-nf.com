<?php

use LootTracker\Guild\GuildInterface;
use Illuminate\Support\MessageBag as MessageBag;

class GuildController extends BaseController
{

    protected $guild;
    protected $user;

    public function __construct(GuildInterface $guild, \Authority\Repo\User\UserInterface $user)
    {
        $this->guild = $guild;
        $this->user = $user;
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
        $this->user->redirectNonAuthedUser();

        $data = Input::all();
        $user_id = $this->user->getUserID();
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
        $this->user->redirectNonAuthedUser();

        $guild = $this->guild->findId($id);
        if (($this->user->isAdmin()) || ($this->user->getUser()->hasAccess('guild_'.$guild->tag.'_admins'))) {
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
        $this->user->redirectNonAuthedUser();

        $data = Input::all();
        $guild = $this->guild->findId($id);

        //Check if the user is admin or fail
        if ((($this->user->isAdmin()) || ($this->user->getUser()->hasAccess('guild_'.$guild->tag.'_admins')))) {
            if (!is_numeric($id)) {
                App::abort(404);
            }

            if ($this->guild->validator->with($data)->passes()) {
                $this->guild->update($data);

                // Success!
                Session::flash('success', 'Guild "' . $guild->name . '" updated.');
                return Redirect::to('guilds');
            } else {
                Session::flash('error', 'Error updating the guild information.');
                return Redirect::action('GuildController@edit', array($id))->withInput()->withErrors($this->guild->validator->errors());
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
        $this->user->redirectNonAuthedUser();
        $guild = $this->guild->findId($id);

        //Check if the user is admin or fail
        if (($this->user->isAdmin()) || ($this->user->getUser()->hasAccess('guild_'.$guild->tag.'_admins'))) {
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
        $this->user->redirectNonAuthedUser();
        $user = $this->user->byUsername(Input::get('username'));
        if ($user == null)
            return Redirect::back()->withErrors('User not found.');

        //Get the guild or return an error.
        $guild_id = Input::get('guild_id');
        $guild = $this->guild->findId($guild_id);
        if ($guild == null)
            return Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || $this->user->getUser()->hasAccess('guild_'.$guild->tag.'_admins')))
            return Redirect::back()->withErrors(array('username' => 'You do not have sufficient permissions.'));

        $this->guild->addMember($guild_id, $user->id);
        return Redirect::back();
    }

    public function removeMember()
    {
        $this->user->redirectNonAuthedUser();

        $user = $this->user->byUsername(Input::get('username'));
        if ($user == null)
            return Redirect::back()->withErrors('User not found.');

        //Get the guild or return an error.
        $guild_id = Input::get('guild_id');
        $guild = $this->guild->findId($guild_id);
        if ($guild == null)
            return Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || $this->user->getUser()->hasAccess('guild_'.$guild->tag.'_admins')))
            return Redirect::back()->withErrors('You do not have sufficient permissions.');

        $this->guild->removeMember($guild_id, $user->id);
        return Redirect::back();
    }


    public function removeMemberById($guild_id, $user_id)
    {
        $this->user->redirectNonAuthedUser();

        $user = $this->user->byUsername(Input::get('username'));
        if ($user == null)
            return Redirect::back()->withErrors('User not found.');

        //Get the guild or return an error.
        $guild = $this->guild->findId($guild_id);
        if ($guild == null)
            return Redirect::back()->withErrors('Guild not found.');

        //Check if the user has permission to do this action.
        if (!($this->user->isAdmin() || $this->user->getUser()->hasAccess('guild_'.$guild->tag.'_admins')))
            return Redirect::back()->withErrors('You do not have sufficient permissions.');

        $this->guild->removeMember($guild_id, $user->id);
        return Redirect::back();
    }
    public function promoteMemberByTag($guild_tag, $user_id)
    {
        $this->user->redirectNonAuthedUser();

        $guild = $this->guild->findTag($guild_tag);
        $this->promoteMember($guild->id, $user_id);
    }

    public function promoteMember($guild_id, $user_id)
    {
        $this->user->redirectNonAuthedUser();

        $this->guild->promoteMember($guild_id, $user_id);
        return Redirect::back();
    }

    public function demoteMemberByTag($guild_tag, $user_id)
    {
        $this->user->redirectNonAuthedUser();

        $guild = $this->guild->findTag($guild_tag);
        $this->demoteMember($guild->id, $user_id);
    }

    public function demoteMember($guild_id, $user_id)
    {
        $this->user->redirectNonAuthedUser();

        $this->guild->demoteMember($guild_id, $user_id);
        return Redirect::back();
    }
}

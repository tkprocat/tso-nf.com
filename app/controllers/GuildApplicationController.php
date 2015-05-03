<?php

use Authority\Repo\User\UserInterface;
use LootTracker\Guild\GuildInterface;

class GuildApplicationController extends BaseController
{


    protected $guild;
    protected $user;
    protected $sentry;

    /**
     * @param GuildInterface $guild
     * @param GuildApplication $guild_application
     * @param UserInterface $user
     * @param Sentry $sentry
     */
    public function __construct(GuildInterface $guild, UserInterface $user)
    {
        $this->guild = $guild;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $guildapplications = $this->guild->getAllApplications($id);

        return View::make('guildapplications.index')->with('guildapplications', $guildapplications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return Response
     */
    public function create($id)
    {
        $guild = $this->guild->findId($id);
        if ($guild != null) {
            return View::make('guildapplications.create')->with('guild', $guild);
        } else {
            Session::flash('error', 'No such guild.');
            return Redirect::to('guilds');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $user = $this->user->getUser();
        //Check if the user is admin or fail
        if (($user != null) && ($user->hasPermission('admin'))) {
            // Form Processing
            if ((Input::get('guild_id') > 0) && (is_numeric(Input::get('guild_id')))) {
                $guild = $this->guild->findId(Input::get('guild_id'));
                //TODO move to repository
                $this->guild->addGuildApplication($guild->id, $user->id);

                // Success!
                Session::flash('success', 'Your application to join "' . $guild->name . '" has been sent.');
                return Redirect::to('guilds');
            } else {
                Session::flash('error', 'Error in sending guild application, plrease try again.');
                return Redirect::action('GuildApplicationController@create')
                    ->withInput();
            }
        } else {
            return Redirect::route('login');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $guild = GuildApplication::find($id);
        if (Sentry::hasAccess('admin') === true) {
            return View::make('guildapplications.edit')->with('guild', $guild);
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
        $user = $this->user->getUser();
        //Check if the user is admin or fail
        if (($user != null) && ($user->hasPermission('admin'))) {
            if (!is_numeric($id)) {
                App::abort(404);
            }

            $validator = GuildApplication::validate(Input::all());
            if ($validator->passes()) {
                $guild = GuildApplication::find($id);
                $guild->update(array(
                    'name' => Input::get('name'),
                    'tag' => Input::get('tag'),
                ));

                // Success!
                Session::flash('success', 'Guild "' . Input::get('name') . '" updated.');
                return Redirect::to('guilds');
            } else {
                Session::flash('error', 'Error updating the guild information.');
                return Redirect::action('UserController@edit', array($id))
                    ->withInput()
                    ->withErrors($validator->getMessageBag());
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
        $user = $this->user->getUser();
        //Check if the user is admin or fail
        if (($user != null) && ($user->hasPermission('admin'))) {
            if (!is_numeric($id)) {
                // @codeCoverageIgnoreStart
                return App::abort(404);
                // @codeCoverageIgnoreEnd
            }

            $guild = GuildApplication::find($id);
            if (($guild != null) && ($guild->delete()))
            {
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
}

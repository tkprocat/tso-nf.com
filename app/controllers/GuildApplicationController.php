<?php

class GuildApplicationController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $guildapplications = GuildApplication::orderBy('name')->get();

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
        $guild = Guild::find($id);
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
        $user = Sentry::getUser();
        //Check if the user is admin or fail
        if (($user != null) && ($user->hasPermission('admin'))) {
            // Form Processing
            if ((Input::get('guild_id') > 0) && (is_numeric(Input::get('guild_id')))) {
                $guild = Guild::find(Input::get('guild_id'));
                GuildApplication::create(array(
                    'guild_id' => Input::get('guild_id'),
                    'user_id' => Sentry::getUser()->id,
                ));

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
        $user = Sentry::getUser();
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
        $user = Sentry::getUser();
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

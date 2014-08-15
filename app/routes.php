<?php
//Make blog the start page.
Route::get('/', function() {
    return Redirect::to('blog');
});

// Session Routes
Route::get('login', array('as' => 'login', 'uses' => 'SessionController@create'));
Route::get('logout', array('as' => 'logout', 'uses' => 'SessionController@destroy'));
Route::resource('sessions', 'SessionController', array('only' => array('create', 'store', 'destroy')));

//User Routes
Route::get('register', 'UserController@create');
Route::get('users/{id}/activate/{code}', 'UserController@activate')->where('id', '[0-9]+');
Route::get('resend', array('as' => 'resendActivationForm', function()
{
    return View::make('users.resend');
}));

Route::post('resend', 'UserController@resend');
Route::get('forgot', array('as' => 'forgotPasswordForm', function()
{
    return View::make('users.forgot');
}));

Route::post('forgot', 'UserController@forgot');
Route::post('users/{id}/change', 'UserController@change');
Route::get('users/{id}/reset/{code}', 'UserController@reset')->where('id', '[0-9]+');
Route::resource('users', 'UserController');

//Loot
Route::post('loot/getJSONLoot', 'LootController@getLootForAdventure');
Route::get('loot/getJSONLoot', 'LootController@getLootForAdventure');

//Stats
Route::get('stats/global', 'StatsController@getGlobalStats');

//Authenticated Routes
Route::group(array('before' => 'auth'), function()
{
    //Blog
    Route::get('blog/create', 'BlogPostController@create');
    Route::get('blog/{id}/edit', 'BlogPostController@edit');
    Route::post('blog/store', 'BlogPostController@store');
    Route::put('blog/update', 'BlogPostController@update');
    Route::get('blog/{id}/comment/create', 'BlogCommentController@create')->where('id', '[0-9]+');
    Route::get('blog/comment/{id}/edit', 'BlogCommentController@edit')->where('id', '[0-9]+');
    Route::post('blog/comment/store', 'BlogCommentController@store');
    Route::put('blog/comment/update', 'BlogCommentController@update');

    //Loot
    Route::get('loot/create', 'LootController@create');
    Route::get('loot/createpopup', 'LootController@createpopup');
    Route::post('loot/delete', 'LootController@delete');
    Route::get('loot/{username}/{adventure}', 'LootController@show');
    Route::resource('loot', 'LootController');

    //Guilds
    Route::get('guilds/applications/create/{id}', 'GuildApplicationController@create');
    Route::resource('guilds', 'GuildController');
    Route::resource('guildapplications', 'GuildApplicationController');
    Route::get('guilds/{guild_id}/promote/{user_id}', 'GuildController@promoteMember')->where('guild_id', '[0-9]+');
    Route::get('guilds/{guild_id}/demote/{user_id}', 'GuildController@demoteMember')->where('guild_id', '[0-9]+');
    Route::get('guilds/{guild_tag}/promote/{user_id}', 'GuildController@promoteMemberByTag')->where('guild_tag', '[0-9A-Za-z]+');
    Route::get('guilds/{guild_tag}/demote/{user_id}', 'GuildController@demoteMemberByTag')->where('guild_tag', '[0-9A-Za-z]+');
    Route::post('guilds/addMember', array('as' => 'guildAddMember', 'uses' => 'GuildController@addMember'));

    //Labs
    Route::get('labs', 'LabController@labs');
    Route::get('labs/lab3', 'LabController@lab3');
    Route::get('labs/getTop10BestAdventuresForLootTypeByAvgDrop', 'LabController@getTop10BestAdventuresForLootTypeByAvgDrop');
    Route::get('labs/getTop10BestAdventuresForLootTypeByDropChance', 'LabController@getTop10BestAdventuresForLootTypeByDropChance');

    //Stats
    Route::get('stats/personal', 'StatsController@getPersonalStats');
    Route::get('stats/personal/{username}', 'StatsController@getPersonalStats');
    Route::get('stats/getJSONLootTypes', 'StatsController@getJSONLootTypes');
    Route::get('/stats/', array('as' => 'stats', 'uses' => 'StatsController@getPersonalStats'));
    Route::get('stats/accumulatedloot/{username}/{datefrom}/{dateto}', array('uses' => 'StatsController@getAccumulatedLootBetween'))->where('datefrom', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$')->where('dateto', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$');
    Route::get('stats/adventuresplayed/{username}/{datefrom}/{dateto}', array('uses' => 'StatsController@getAdventuresPlayedBetween'))->where('datefrom', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$')->where('dateto', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$');
    Route::get('stats/top10bydrop', array('as' => 'stats', 'uses' => 'StatsController@showTop10BestAdventuresForLootTypeByAvgDrop'));
    Route::get('stats/top10bydropchance', array('as' => 'stats', 'uses' => 'StatsController@showTop10BestAdventuresForLootTypeByDropChance'));
    Route::get('stats/getTop10BestAdventuresForLootTypeByAvgDrop', array('as' => 'stats', 'uses' => 'StatsController@getTop10BestAdventuresForLootTypeByAvgDrop'));
    Route::get('stats/getTop10BestAdventuresForLootTypeByDropChance', array('as' => 'stats', 'uses' => 'StatsController@getTop10BestAdventuresForLootTypeByDropChance'));
});

Route::get('blog', array('as' => 'blog', 'uses' => 'BlogPostController@index'));
Route::get('blog/{slug}', 'BlogPostController@show');

Route::get('guilds', 'GuildController@index');
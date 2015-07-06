<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//User management
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

//Loot
Route::group(array('middleware' => ['auth'], 'permission' => ['add-loot']), function () {
    Route::get('loot/createpopup', 'LootController@createpopup');
    Route::get('loot/adventure/{adventure}', 'LootController@index');
    Route::resource('loot', 'LootController', ['except' => ['index', 'show']]);
});

Route::group(array('middleware' => ['auth'], 'permission' => ['see-loot']), function () {
    Route::post('loot/getJSONLoot', 'LootController@getLootForAdventure');
    Route::get('loot/getJSONLoot', 'LootController@getLootForAdventure');
    Route::get('loot/adventure/{adventure}', 'LootController@index');
    Route::get('loot/{username}/{adventure}', 'LootController@show');
    Route::resource('loot', 'LootController', ['only' => ['index', 'show']]);
});

//Blog
Route::group(array('middleware' => ['auth'], 'permission' => ['admin-blog']), function () {
    Route::resource('blog/{post}/comment', 'BlogCommentController', ['only' => 'create']);
    Route::resource('blog/comment', 'BlogCommentController', ['except' => ['index', 'create']]);
    Route::resource('blog', 'BlogPostController', ['except' => ['index', 'show']]);
});

//Guilds
Route::group(array('middleware' => ['auth'], 'role' => ['user', 'admin'], 'permission' => ''), function () {
    //Guilds
    Route::resource('guilds', 'GuildController');
    Route::resource('guildapplications', 'GuildApplicationController');

    Route::group(array('middleware' => ['auth'], 'role' => ['admin'], 'permission' => 'admin-guild'), function () {
        Route::get('guilds/{guild_id}/promote/{user_id}', 'GuildController@promoteMember')->where('guild_id', '[0-9]+')->where('user_id', '[0-9]+');
        Route::get('guilds/{guild_id}/demote/{user_id}', 'GuildController@demoteMember')->where('guild_id', '[0-9]+')->where('user_id', '[0-9]+');
        Route::get('guilds/{guild_id}/add/{user_id}', 'GuildController@addMember')->where('guild_id', '[0-9]+')->where('user_id', '[0-9]+');
        Route::get('guilds/{guild_id}/kick/{user_id}', 'GuildController@removeMember')->where('guild_id', '[0-9]+')->where('user_id', '[0-9]+');
    });
});

//General stats
Route::group(array('middleware' => ['auth'], 'permission' => ['see-loot']), function () {
    Route::get('stats/getJSONLootTypes', 'GeneralStatsController@getJSONLootTypes');
    Route::get('stats/getLast10Weeks', 'GeneralStatsController@getLast10Weeks');
    Route::get('stats/getLast30Days', 'GeneralStatsController@getLast30Days');
    Route::get('stats/getSubmissionsForTheLast10Weeks', 'GeneralStatsController@getSubmissionsForTheLast10Weeks');
    Route::get('stats/getNewUserCountForTheLast10Weeks', 'GeneralStatsController@getNewUserCountForTheLast10Weeks');
});

//Global stats
Route::group(array('middleware' => ['auth'], 'permission' => ['see-loot']), function () {
    Route::get('stats/global/top10bydrop',
        array('as' => 'stats', 'uses' => 'GlobalStatsController@showTop10BestAdventuresForLootTypeByAvgDrop'));
    Route::get('stats/global/getTop10BestAdventuresForLootTypeByAvgDrop/{type}',
        array('as' => 'stats', 'uses' => 'GlobalStatsController@getTop10BestAdventuresForLootTypeByAvgDrop'));
    Route::get('stats/global', 'GlobalStatsController@index');
    Route::get('stats/global/submissionrate',
        array('as' => 'stats', 'uses' => 'GlobalStatsController@showSubmissionRate'));
    Route::get('stats/global/newuserrate',
        array('as' => 'stats', 'uses' => 'GlobalStatsController@showNewUserRate'));
    Route::get('stats/global/getPlayedCountForLast30Days/{adventure}',
        array('as' => 'stats', 'uses' => 'GlobalStatsController@getPlayedCountForLast30Days'));
    Route::get('stats/global/{adventurename}', 'GlobalStatsController@show');
});

//Personal stats
Route::group(array('middleware' => ['auth'], 'permission' => ['see-loot']), function () {
    Route::get('stats/personal/{username?}', 'PersonalStatsController@getPersonalStats');
    Route::get('stats/personal/accumulatedloot/{username}/{datefrom}/{dateto}',
        array('uses' => 'PersonalStatsController@getAccumulatedLootBetween'))->
        where('datefrom', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$')->
        where('dateto', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$');
    Route::get('stats/personal/adventuresplayed/{username}/{datefrom}/{dateto}',
        array('uses' => 'PersonalStatsController@getAdventuresPlayedBetween'))->
        where('datefrom', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$')->
        where('dateto', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$');
});

//Users
Route::group(array('middleware' => ['auth']), function () {
    Route::resource('users', 'UserController');
});

//Prices
Route::group(array('middleware' => ['auth'], 'permission' => ['see-prices']), function () {

    Route::resource('prices', 'PriceController');
});

//Admin prices
Route::group(array('middleware' => ['auth'], 'permission' => ['edit-prices'], 'role' => ['admin']), function () {
    Route::resource('admin/prices', 'AdminPriceListController');
    Route::get('admin/prices/{id}/newprice', 'AdminPriceListController@createNewPrice');
    Route::post('admin/prices/newprice/{id}', 'AdminPriceListController@storeNewPrice');
});


Route::group(array('middleware' => ['auth'], 'role' => ['admin']), function () {
    //Admin/Adventure
    Route::get('admin/adventures/create', 'AdminAdventureController@create');
    Route::get('admin/adventures/getItemTypes', 'AdminAdventureController@getItemTypes');
    Route::get('admin/adventures/getAdventureTypes', 'AdminAdventureController@getAdventureTypes');
    Route::resource('admin/adventures', 'AdminAdventureController');

    //Admin
    Route::get('admin', 'AdminController@index');

    //Admin users
    Route::resource('admin/users', 'AdminUserController');
});

//Blog
Route::get('/', 'BlogPostController@index');
Route::resource('blog', 'BlogPostController', ['only' => ['index', 'show']]);
Route::resource('blog/{post}/comment', 'BlogCommentController', ['only' => ['index', 'show']]);

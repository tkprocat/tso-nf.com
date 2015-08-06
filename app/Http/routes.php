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
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

//Loot
Route::group(['middleware' => ['auth'], 'permission' => ['add-loot']], function () {
    Route::get('loot/adventure/{adventure}', 'LootController@index');
    Route::resource('loot', 'LootController', ['except' => ['index', 'show']]);
});

Route::group(['middleware' => ['auth'], 'permission' => ['see-loot']], function () {
    Route::post('loot/getJSONLoot', 'LootController@getLootForAdventure');
    Route::get('loot/getJSONLoot', 'LootController@getLootForAdventure');
    Route::get('loot/adventure/{adventure}', 'LootController@index');
    Route::get('loot/id/{id}', 'LootController@show');
    Route::get('loot/user/{username}/{adventure}', 'LootController@indexByUser');
    Route::resource('loot', 'LootController', ['only' => ['index', 'show']]);
});

//Blog
Route::group(['middleware' => ['auth'], 'permission' => ['admin-blog']], function () {
    Route::resource('blog/{post}/comment', 'BlogCommentController', ['only' => 'create']);
    Route::resource('blog/comment', 'BlogCommentController', ['except' => ['index', 'create']]);
    Route::resource('blog', 'BlogPostController', ['except' => ['index', 'show']]);
});

//Guilds
Route::group(['middleware' => ['auth'], 'role' => ['user', 'admin'], 'permission' => ''], function () {
    //Guilds
    Route::resource('guilds', 'GuildController');

    Route::group(['middleware' => ['auth'], 'role' => ['admin'], 'permission' => 'admin-guild'], function () {
        Route::get('guilds/{guild_id}/promote/{user_id}', 'GuildController@promoteMember')->where('guild_id',
            '[0-9]+')->where('user_id', '[0-9]+');

        Route::get('guilds/{guild_id}/demote/{user_id}', 'GuildController@demoteMember')->where('guild_id',
            '[0-9]+')->where('user_id', '[0-9]+');

        Route::get('guilds/{guild_id}/add/{user_id}', 'GuildController@addMember')->where('guild_id',
            '[0-9]+')->where('user_id', '[0-9]+');

        Route::get('guilds/{guild_id}/kick/{user_id}', 'GuildController@removeMember')->where('guild_id',
            '[0-9]+')->where('user_id', '[0-9]+');
        Route::post('guilds/{guild_id}/add', 'GuildController@addMemberPost')->where('guild_id', '[0-9]+');

        Route::resource('guilds/{id}/applications', 'GuildApplicationController');
        Route::get('guilds/{guild_id}/applications/{application_id}/approve', 'GuildApplicationController@approve');
        Route::get('guilds/{guild_id}/applications/{application_id}/decline', 'GuildApplicationController@decline');
    });
});

//Guild applications
Route::group(['middleware' => ['auth'], 'role' => ['user']], function () {
    Route::get('guilds/{guild_id}/applications/create', 'GuildApplicationController@create');
    Route::post('guilds/{guild_id}/applications/', 'GuildApplicationController@store');
});

//General stats
Route::group(['middleware' => ['auth'], 'permission' => ['see-loot']], function () {
    Route::get('stats/getJSONLootTypes', 'GeneralStatsController@getJSONLootTypes');
    Route::get('stats/getLast10Weeks', 'GeneralStatsController@getLast10Weeks');
    Route::get('stats/getLast30Days', 'GeneralStatsController@getLast30Days');
    Route::get('stats/getSubmissionsForTheLast10Weeks', 'GeneralStatsController@getSubmissionsForTheLast10Weeks');
    Route::get('stats/getNewUserCountForTheLast10Weeks', 'GeneralStatsController@getNewUserCountForTheLast10Weeks');
});

//Global stats
Route::group(['middleware' => ['auth'], 'permission' => ['see-loot']], function () {
    Route::get('stats/global/top10bydrop', 'GlobalStatsController@showTop10BestAdventuresForLootTypeByAvgDrop');
    Route::get('stats/global/getTop10BestAdventuresForLootTypeByAvgDrop/{type}',
        'GlobalStatsController@getTop10BestAdventuresForLootTypeByAvgDrop');

    Route::get('stats/global', 'GlobalStatsController@index');
    Route::get('stats/global/submissionrate', 'GlobalStatsController@showSpubmissionRate');

    Route::get('stats/global/newuserrate', ['as' => 'stats', 'uses' => 'GlobalStatsController@showNewUserRate']);
    Route::get('stats/global/getPlayedCountForLast30Days/{adventure}',
        'GlobalStatsController@getPlayedCountForLast30Days');

    Route::get('stats/global/{adventurename}', 'GlobalStatsController@show');
});

//Guild stats
Route::group(['middleware' => ['auth'], 'role' => ['guild_member']], function () {
    Route::get('stats/guild', 'GuildStatsController@index');
    Route::get('stats/guild/submissionrate', ['as' => 'stats', 'uses' => 'GuildStatsController@showSubmissionRate']);
    Route::get('stats/guild/newuserrate', ['as' => 'stats', 'uses' => 'GuildStatsController@showNewUserRate']);
    Route::get(
        'stats/guild/getPlayedCountForLast30Days/{adventure}',
        'GuildStatsController@getPlayedCountForLast30Days'
    );

    Route::get('stats/guild/adventure/{adventurename}', 'GuildStatsController@show');
});

//Personal stats
Route::group(['middleware' => ['auth'], 'permission' => ['see-loot']], function () {
    Route::get('stats/personal/{username?}', 'PersonalStatsController@getPersonalStats');

    Route::get(
        'stats/personal/accumulatedloot/{username}/{datefrom}/{dateto}',
        'PersonalStatsController@getAccumulatedLootBetween'
    )->where('datefrom', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$')
     ->where('dateto', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$');

    Route::get(
        'stats/personal/adventuresplayed/{username}/{datefrom}/{dateto}',
        'PersonalStatsController@getAdventuresPlayedBetween'
    )->where('datefrom', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$')
     ->where('dateto', '^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$');
});

//Users
Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UserController');
});

//Prices
Route::group(['middleware' => ['auth'], 'permission' => ['see-prices']], function () {
    Route::get('prices/advancedcalc', 'PriceController@showAdvancedCalc');
    Route::get('prices/simplecalc', 'PriceController@showSimpleCalc');
    Route::get('prices/getItemsWithPrices', 'PriceController@getItemsWithPrices');
    Route::resource('prices', 'PriceController');
});

//Admin prices
Route::group(['middleware' => ['auth'], 'permission' => ['edit-prices'], 'role' => ['admin']], function () {
    Route::resource('admin/prices', 'AdminPriceController');
    Route::get('admin/prices/{id}/newprice', 'AdminPriceController@createNewPrice');
    Route::post('admin/prices/newprice/{id}', 'AdminPriceController@storeNewPrice');
});

//Admin items
Route::group(['middleware' => ['auth'], 'permission' => ['edit-prices', 'edit-items'], 'role' => ['admin', 'prices_admin']], function () {
    Route::resource('admin/items', 'AdminItemController');
});

Route::group(['middleware' => ['auth'], 'role' => ['admin']], function () {
    //Admin/Adventure
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

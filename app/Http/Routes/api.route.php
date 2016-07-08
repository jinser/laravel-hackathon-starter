<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/api', function () {
        return view('apidashboard');
    });
    
    Route::post('/slack/message', [
        'uses' => 'SlackController@sendMessageToTeam',
        'as'   => 'slack.message'
    ]);

    Route::post('/tweet/new', [
        'uses' => 'TwitterController@sendTweet',
        'as'   => 'tweet.new',
        'middleware' => ['auth']
    ]);
/*    
    Route::group(['prefix' => 'api'], function() {
        
        Route::get('github', [
            'uses' => 'GithubController@getPage',
            'as'   => 'api.github',
            'middleware' => ['auth']
        ]);

        Route::get('twitter', [
            'uses' => 'TwitterController@getPage',
            'as'   => 'api.twitter',
            'middleware' => ['auth']
        ]);

        Route::get('lastfm', [
            'uses' => 'LastFmController@getPage',
            'as'   => 'api.lastfm'
        ]);

        Route::get('nyt', [
            'uses' => 'NytController@getPage',
            'as'   => 'api.nyt'
        ]);

        Route::get('steam', [
            'uses' => 'SteamController@getPage',
            'as'   => 'api.steam'
        ]);

        Route::get('stripe', [
            'uses' => 'StripeController@getPage',
            'as'   => 'api.stripe'
        ]);

        Route::get('paypal', [
            'uses' => 'PaypalController@getPage',
            'as'   => 'api.paypal'
        ]);

        Route::get('twilio', [
            'uses' => 'TwilioController@getPage',
            'as'   => 'api.twilio'
        ]);

        Route::post('twilio', [
            'uses' => 'TwilioController@sendTextMessage'
        ]);

        Route::get('scraping', [
            'uses' => 'WebScrapingController@getPage',
            'as'   => 'api.scraping'
        ]);

        Route::get('yahoo', [
            'uses' => 'YahooController@getPage',
            'as'   => 'api.yahoo'
        ]);

        Route::get('clockwork', [
            'uses' => 'ClockworkController@getPage',
            'as'   => 'api.clockwork'
        ]);

        Route::post('clockwork', [
            'uses' => 'ClockworkController@sendTextMessage'
        ]);

        Route::get('aviary', [
            'uses' => 'AviaryController@getPage',
            'as'   => 'api.aviary'
        ]);

        Route::get('lob', [
            'uses' => 'LobController@getPage',
            'as'   => 'api.lob'
        ]);

        Route::get('slack', [
            'uses' => 'SlackController@getPage',
            'as'   => 'api.slack'
        ]);

        Route::get('facebook', [
            'uses' => 'FacebookController@getPage',
            'as'   => 'api.facebook',
            'middleware' => ['auth']
        ]);

        Route::get('linkedin', [
            'uses' => 'LinkedInController@getPage',
            'as'   => 'api.linkedin',
            'middleware' => ['auth']
        ]);

        Route::get('foursquare', [
            'uses' => 'FoursquareController@getPage',
            'as'   => 'api.foursquare'
        ]);

        Route::get('instagram', [
            'uses' => 'InstagramController@getPage',
            'as'   => 'api.instagram',
            'middleware' => ['auth']
        ]);

        Route::get('tumblr', [
            'uses' => 'TumblrController@getPage',
            'as'   => 'api.tumblr'
        ]);
    });
*/
});

/*
|--------------------------------------------------------------------------
| API Resource Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth.basic'], 'prefix' => 'api'], function() {
    Route::group(['middleware' => ['permission:create_pricingplan']], function() {
        Route::resource('pricingplans','Api\PricingPlanApiController');
        //Route::get('api/pricingplans',['uses' => 'Api\PricingPlanApiController@index']);
    });

    Route::group(['middleware' => ['permission:create_livepricingplan']], function() {
     
        Route::resource('livepricingplans','Api\LivePricingPlanApiController');
    });
    
    Route::group(['middleware' => ['permission:create_customeraccount']], function() {
     
        Route::resource('customeraccount','Api\CustomerAccountApiController', ['except'=> 'index','create','store']);
    });
    
    Route::group(['middleware' => ['permission:create_subscription']], function() {
     
        Route::resource('subscriptions','Api\SubscriptionApiController');
    });
    
});


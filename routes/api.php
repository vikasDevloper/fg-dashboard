<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::get('/user', function (Request $request) {
//      return $request->user();
//  })->middleware([

//  	'client_credentials'
//  ]);

// Route::post('login', 'API\UserController2@login');
// Route::post('register', 'API\UserController2@register');

Route::group(['middleware' => 'client_credentials'], function () {

		//Route::get('test_api', 'Web\Dashboard\Dashboard@testApi');
		Route::get('getexibition', 'Api\UserController@getexibition');
		Route::post('getOnlineTurnover', 'Api\UserController@getTurnover');
		Route::post('getMonthlyRevenue', 'Api\UserController@getMonthlyRevenue');
		Route::post('dailyOnlineTurnover', 'Api\UserController@dailyTurnover');
		Route::post('dailyMonthlyRevenue', 'Api\UserController@dailyMonthlyRevenue');
		Route::post('dailyOflineTurnover', 'Api\UserController@dailyOfflineTurnover');
		Route::post('offlineMonthlyRevenue', 'Api\UserController@offlineMonthlyRevenue');
		Route::post('perDayRevenue', 'Api\UserController@perDayRevenue');
		Route::post('productPerformance', 'Api\UserController@productPerformance');
		Route::post('url-short', 'Web\Dashboard\UrlShortnerLink@store');
		Route::post('url-edit', 'Web\Dashboard\UrlShortnerLink@editUrl');
		Route::post('get-short-url', 'Web\Dashboard\UrlShortnerLink@getShortUrl');
		Route::post('get-long-url', 'Web\Dashboard\UrlShortnerLink@getLongUrl');
 
		Route::get('get-online-buyers', 'Api\DBSyncController@GetOnlineBuyer');
		Route::get('get-city-users', 'Api\DBSyncController@cityWiseUsers');
		Route::get('get-offline-buyers', 'Api\DBSyncController@offlineUsers');
		Route::get('get-newsletter-subscribers', 'Api\DBSyncController@newsletterSuscriber');
		Route::get('get-exhibition-cities', 'Api\DBSyncController@exhibition_cities');
		Route::get('update-online-buyers', 'Api\DBSyncController@updatedOnlineBuyer');
		Route::get('update-offline-buyers', 'Api\DBSyncController@updatedOfflineBuyer');
		Route::get('update-newsletter-subscribers', 'Api\DBSyncController@updatedNewsletterSuscriber');


	});

/*
 *  Amazon SNS setup
 */

//Route::post('/handle-bounces', 'Web\AmazonSNSController@handleBounces');

<?php

use Illuminate\Http\Request;

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

/*
 * API version 1
 */

Route::group(['prefix' => 'v1'], function() {
	Route::middleware('auth:api')->get('/user', function (Request $request) {
	    return $request->user();
	});

	Route::post('signup', 'AuthController@register'); 
	Route::post('login', 'AuthController@login');
	Route::get('fogot', 'AuthController@fogotPassword');
	Route::post('new-password', 'AuthController@newPassword');

	Route::group(['middleware' => 'jwt.auth'], function () {
		Route::get('auth', 'AuthController@user');
		Route::post('logout', 'AuthController@logout');
		Route::post('change-password', 'AuthController@changePassword');
		
		Route::group(['namespace' => 'API'], function() {		    
		
			Route::group(['prefix' => 'booking'], function() {
			    Route::get('get-new','BookingController@getNew');
			    Route::get('get-confirm','BookingController@getConfirm');
			    Route::put('confirm/{id}','BookingController@confirm');
			    Route::put('cancel/{id}','BookingController@cancel');
			    Route::post('new', 'BookingController@create');
			    Route::get('/detail/{id}','BookingController@getBookingDetail');
			    Route::get('get-totals', 'BookingController@getTotals');
			});

			Route::group(['prefix' => 'schedule'], function() {
			    Route::get('/','ScheduleController@getSchedule');
			    
			});

			Route::group(['prefix' => 'place'], function() {
			    Route::get('get-action-date', 'PlaceController@getActionDate');
			});

			Route::group(['prefix' => 'services'], function() {
			    Route::get('/','ServiceController@getServices');
			    Route::get('/list-services','ServiceController@getListServies');
			});

			Route::group(['prefix' => 'app-banners'], function() {
			    Route::get('/get-by-app-id', 'AppBannersController@getAppBannersByAppId');
			});

			Route::group(['prefix' => 'reviews'], function() {
			    Route::get('/get-by-type','ReviewController@getByType');
			});


            Route::post('add-device-token', 'UserController@addDeviceToken');

		});
	});

	Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');

	Route::get('check_user', 'AuthController@check_user');
        

        Route::group(['prefix' => 'webnail', 'namespace' => 'API', 'middleware' => ['api','cors']], function () {    
            Route::post('booking-order-web', 'WebBookingController@bookingOrderWeb');
            Route::post('list-promotion', 'WebBookingController@listPromotion');
            Route::post('list-service-by-cate', 'WebBookingController@listServiceByCate');
        });
});



// Route::get('testApi', 'API\testAPIController@index');
// Route::get('test', 'API\testAPIController@get');
// Route::get('testOnesignal', 'API\testAPIController@sendOnesignal')->middleware('jwt.auth');


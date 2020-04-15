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

use Illuminate\Support\Facades\Route;

Route::
//middleware('auth:api')->
prefix('corona')->group(
    function () {
        Route::get('/status/total', 'StatusController@total');
        Route::match(['get', 'post'], '/status', 'StatusController@create');

        Route::post('/status/update', 'StatusController@update');
        Route::get('/statuses/page/{page}', 'StatusController@byPage');
        Route::get('/statuses/page/{page?}/limit/{limit?}/order/{order?}/direction/{direction?}', 'StatusController@byPage');
        Route::get('/statuses[/order/{order}/direction/{direction}/limit/{limit}]', 'StatusController@index');
        Route::get('/status/country/{country_id}/last_date/{date}', 'StatusController@countryDate');
        Route::get('/status/country/{id}', 'StatusController@countryById');

        Route::get('/countries', 'CountryController@all');
//        Route::post('/countries', 'CountryController@createCountries');
        Route::get('/country/{id}', 'CountryController@show');
        Route::match(['get', 'post'], '/telegram', 'StatusController@notify');
        Route::post('', 'StatusController@create');
});


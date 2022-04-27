<?php

/*
|--------------------------------------------------------------------------
| Provider Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['provider_locale']], function () {

	Route::get('/', 'ProviderController@index')->name('index');
	Route::get('/trips', 'ProviderResources\TripController@history')->name('trips');

	Route::get('/incoming', 'ProviderController@incoming')->name('incoming');
	Route::post('/request/{id}', 'ProviderController@accept')->name('accept');
	Route::patch('/request/{id}', 'ProviderController@update')->name('update');
	Route::post('/request/{id}/rate', 'ProviderController@rating')->name('rating');
	Route::delete('/request/{id}', 'ProviderController@reject')->name('reject');
	Route::post('/request/bidding/{id}', 'ProviderController@bidding')->name('bidding');


	// Route::get('/test', 'ProviderController@start_service')->name('test');
	Route::get('/request/service', 'ProviderController@start_service')->name('start_service');

	Route::get('/earnings', 'ProviderController@earnings')->name('earnings');
	Route::get('/upcoming', 'ProviderController@upcoming_trips')->name('upcoming');
	Route::get('/cancel', 'ProviderController@cancel')->name('cancel');

	Route::resource('documents', 'ProviderResources\DocumentController');

	Route::get('/profile', 'ProviderResources\ProfileController@show')->name('profile.index');
	Route::post('/profile', 'ProviderResources\ProfileController@store')->name('profile.update');

	Route::get('/location', 'ProviderController@location_edit')->name('location.index');
	Route::post('/location', 'ProviderController@location_update')->name('location.update');

	Route::get('/profile/password', 'ProviderController@change_password')->name('change.password');
	Route::post('/change/password', 'ProviderController@update_password')->name('password.update');

	Route::post('/profile/available', 'ProviderController@available')->name('available');

	// help
	Route::get('/help', 'ProviderController@help');
});
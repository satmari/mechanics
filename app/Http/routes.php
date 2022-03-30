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

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


// Route::get('/', 'WelcomeController@index');
Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

// Plant
Route::get('plant','settingsController@plant');
Route::get('add_plant','settingsController@add_plant');
Route::post('add_plant_post', 'settingsController@add_plant_post');
Route::get('edit_plant/{id}','settingsController@edit_plant');
Route::post('edit_plant_post/{id}', 'settingsController@edit_plant_post');
Route::get('remove_plant/{id}', 'settingsController@remove_plant');

// Area
Route::get('area','settingsController@area');
Route::get('add_area','settingsController@add_area');
Route::post('add_area_post', 'settingsController@add_area_post');
Route::get('edit_area/{id}','settingsController@edit_area');
Route::post('edit_area_post/{id}', 'settingsController@edit_area_post');
// Route::get('remove_area/{id}', 'settingsController@remove_area');

// Location
Route::get('location','settingsController@location');
Route::get('add_location','settingsController@add_location');
Route::post('add_location_post', 'settingsController@add_location_post');
Route::get('edit_location/{id}','settingsController@edit_location');
Route::post('edit_location_post/{id}', 'settingsController@edit_location_post');
// Route::get('remove_location/{id}', 'settingsController@remove_location');

// Mechanics
Route::get('mechanics','mechanicsController@index');
Route::post('logincheck','mechanicsController@logincheck');
Route::get('afterlogin','mechanicsController@afterlogin');

Route::get('move_machine_in_plant','mechanicsController@move_machine_in_plant');
Route::post('move_machine_in_plant_loc','mechanicsController@move_machine_in_plant_loc');
Route::post('move_machine_in_plant_scan','mechanicsController@move_machine_in_plant_scan');
Route::get('move_machine_in_plant_remove/{id}/{ses}', 'mechanicsController@move_machine_in_plant_remove');
Route::get('move_machine_in_plant_confirm/{session}', 'mechanicsController@move_machine_in_plant_confirm');

Route::get('transfer_machine','mechanicsController@transfer_machine');
Route::post('transfer_machine_from','mechanicsController@transfer_machine_from');
Route::post('transfer_machine_to','mechanicsController@transfer_machine_to');
Route::post('transfer_machine_scan','mechanicsController@transfer_machine_scan');
Route::get('transfer_machine_remove/{id}/{ses}', 'mechanicsController@transfer_machine_remove');
Route::get('transfer_machine_confirm/{session}', 'mechanicsController@transfer_machine_confirm');

// Admin
Route::get('machines_in_inteos','adminController@machines_in_inteos');
Route::get('update_from_inteos','adminController@update_from_inteos');
Route::get('machines_table','adminController@machines_table');

// Machine


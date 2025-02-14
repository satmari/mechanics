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
Route::get('remove_location/{id}', 'settingsController@remove_location');

// Gadget
Route::get('gadget','settingsController@gadget');
Route::get('add_gadget','settingsController@add_gadget');
Route::post('add_gadget_post', 'settingsController@add_gadget_post');
Route::get('edit_gadget/{id}','settingsController@edit_gadget');
Route::post('edit_gadget_post/{id}', 'settingsController@edit_gadget_post');

// Mechanics
Route::get('mechanics','mechanicsController@index');
Route::post('logincheck','mechanicsController@logincheck');
Route::get('afterlogin','mechanicsController@afterlogin');

Route::get('move_machine_in_plant','mechanicsController@move_machine_in_plant');
Route::post('move_machine_in_plant_loc','mechanicsController@move_machine_in_plant_loc');
Route::post('move_machine_in_plant_scan','mechanicsController@move_machine_in_plant_scan');
Route::get('move_machine_in_plant_remove/{id}/{ses}', 'mechanicsController@move_machine_in_plant_remove');
Route::get('move_machine_in_plant_confirm/{session}', 'mechanicsController@move_machine_in_plant_confirm');
Route::get('move_machine_in_plant_cancel/{session}', 'mechanicsController@move_machine_in_plant_cancel');

Route::get('transfer_machine','mechanicsController@transfer_machine');
Route::post('transfer_machine_from','mechanicsController@transfer_machine_from');
Route::post('transfer_machine_to','mechanicsController@transfer_machine_to');
Route::post('transfer_machine_scan','mechanicsController@transfer_machine_scan');
Route::get('transfer_machine_remove/{id}/{ses}', 'mechanicsController@transfer_machine_remove');
Route::get('transfer_machine_confirm/{session}', 'mechanicsController@transfer_machine_confirm');
Route::get('transfer_machine_cancel/{session}', 'mechanicsController@transfer_machine_cancel');

Route::get('borrow_machine', 'mechanicsController@borrow_machine');

Route::get('give_machine', 'mechanicsController@give_machine');
Route::post('give_machine_to', 'mechanicsController@give_machine_to');
Route::post('give_machine_scan', 'mechanicsController@give_machine_scan');
Route::get('give_machine_remove/{id}/{ses}', 'mechanicsController@give_machine_remove');
Route::get('give_machine_confirm/{session}', 'mechanicsController@give_machine_confirm');
Route::get('give_machine_cancel/{session}', 'mechanicsController@give_machine_cancel');

Route::get('return_machine', 'mechanicsController@return_machine');
Route::post('return_machine_to', 'mechanicsController@return_machine_to');
Route::post('return_machine_scan', 'mechanicsController@return_machine_scan');
Route::get('return_machine_remove/{id}/{ses}', 'mechanicsController@return_machine_remove');
Route::get('return_machine_confirm/{session}', 'mechanicsController@return_machine_confirm');
Route::get('return_machine_cancel/{session}', 'mechanicsController@return_machine_cancel');

Route::get('repair_machine', 'mechanicsController@repair_machine');

Route::get('adjust_machine', 'mechanicsController@adjust_machine');
Route::post('adjust_machine_to', 'mechanicsController@adjust_machine_to');
Route::post('adjust_machine_scan', 'mechanicsController@adjust_machine_scan');
Route::get('adjust_machine_remove/{id}/{ses}', 'mechanicsController@adjust_machine_remove');
Route::get('adjust_machine_confirm/{session}', 'mechanicsController@adjust_machine_confirm');
Route::get('adjust_machine_cancel/{session}', 'mechanicsController@adjust_machine_cancel');

Route::get('fix_machine', 'mechanicsController@fix_machine');
Route::post('fix_machine_to', 'mechanicsController@fix_machine_to');
Route::post('fix_machine_scan', 'mechanicsController@fix_machine_scan');
Route::get('fix_machine_remove/{id}/{ses}', 'mechanicsController@fix_machine_remove');
Route::get('fix_machine_confirm/{session}', 'mechanicsController@fix_machine_confirm');
Route::get('fix_machine_destination', 'mechanicsController@fix_machine_destination');
Route::post('fix_machine_destination_post', 'mechanicsController@fix_machine_destination_post');
Route::get('fix_machine_cancel/{session}', 'mechanicsController@fix_machine_cancel');

Route::get('search_machine', 'mechanicsController@search_machine');
Route::get('search_by_barcode', 'mechanicsController@search_by_barcode');
Route::post('search_by_barcode_scan', 'mechanicsController@search_by_barcode_scan');
Route::get('search_by_location', 'mechanicsController@search_by_location');
Route::post('search_by_location_scan', 'mechanicsController@search_by_location_scan');

Route::get('advanced_search', 'mechanicsController@advanced_search');
Route::post('advanced_search_post', 'mechanicsController@advanced_search_post');

Route::get('add_comment', 'mechanicsController@add_comment');
Route::post('add_comment_scan', 'mechanicsController@add_comment_scan');
Route::post('add_comment_post', 'mechanicsController@add_comment_post');
Route::get('delete_comment_post/{id}', 'mechanicsController@delete_comment_post');
Route::get('delete_comment_post_confirm/{id}', 'mechanicsController@delete_comment_post_confirm');

Route::get('add_info', 'mechanicsController@add_info');
Route::post('add_info_scan', 'mechanicsController@add_info_scan');
Route::post('add_info_post', 'mechanicsController@add_info_post');


Route::get('disable_machine', 'mechanicsController@disable_machine');

Route::get('writeoff_machine_scan', 'mechanicsController@writeoff_machine_scan');
Route::post('writeoff_machine_scan', 'mechanicsController@writeoff_machine_scan');
Route::get('writeoff_machine_remove/{id}/{ses}', 'mechanicsController@writeoff_machine_remove');
Route::get('writeoff_machine_confirm/{session}', 'mechanicsController@writeoff_machine_confirm');
Route::get('writeoff_machine_cancel/{session}', 'mechanicsController@writeoff_machine_cancel');

Route::get('sell_machine', 'mechanicsController@sell_machine');
Route::post('sell_machine_to', 'mechanicsController@sell_machine_to');
Route::post('sell_machine_scan', 'mechanicsController@sell_machine_scan');
Route::get('sell_machine_remove/{id}/{ses}', 'mechanicsController@sell_machine_remove');
Route::get('sell_machine_confirm/{session}', 'mechanicsController@sell_machine_confirm');
Route::get('sell_machine_cancel/{session}', 'mechanicsController@sell_machine_cancel');

Route::get('machine_edit/{machine_id}', 'mechanicsController@machine_edit');
Route::post('machine_edit_post', 'mechanicsController@machine_edit_post');

Route::get('class_table', 'mechanicsController@class_table');
Route::get('add_class','mechanicsController@add_class');
Route::post('add_class_post','mechanicsController@add_class_post');
Route::get('edit_class/{id}','mechanicsController@edit_class');
Route::post('edit_class_post','mechanicsController@edit_class_post');

Route::post('upload_image', 'mechanicsController@upload_image');
Route::post('upload_class_image', 'importImageController@upload_class_image');

// Admin
Route::get('machines_in_inteos','adminController@machines_in_inteos');
Route::get('update_from_inteos','adminController@update_from_inteos');
Route::get('machines_table','adminController@machines_table');

// Workstudy
Route::get('workstudy','workstudyController@index');
Route::post('logincheck_ws','workstudyController@logincheck_ws');
Route::get('afterlogin_ws','workstudyController@afterlogin_ws');
Route::get('add_comment_ws','workstudyController@add_comment_ws');
Route::post('add_comment_ws_scan', 'workstudyController@add_comment_ws_scan');
Route::post('add_comment_ws_post', 'workstudyController@add_comment_ws_post');


// Import

Route::get('import','importController@index');
Route::post('postUpdateRemark', 'importController@postUpdateRemark');
Route::post('postUpdateInfo', 'importController@postUpdateInfo');

Route::post('postImportMachines', 'importController@postImportMachines');


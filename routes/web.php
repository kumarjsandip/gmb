<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth']], function() {
    Route::get('home', 'HomeController@index')->name('home')->middleware('verified');
    Route::post('home/project/create', 'ProjectController@index')->middleware('verified');
    Route::post('home/project/campaign/data/save/{id}', 'ProjectController@projectcampaign')->middleware('verified');
    Route::get('home/project/create/detail/{id}', 'ProjectController@detail')->middleware('verified');
    Route::get('home/campain/create/detail/{id}', 'ProjectController@detailcampain')->middleware('verified');
    Route::get('home/campain/delete/{id}', 'CampaignController@campaindelete')->middleware('verified');
    Route::post('home/campaign/create', 'CampaignController@index')->middleware('verified');
    Route::get('home/generate/csv/{id}', 'ProjectController@generatecsv')->middleware('verified');

    Route::post('home/mashup/save/{id}','MashupController@index');
    
});

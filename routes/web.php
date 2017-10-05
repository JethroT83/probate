<?php

set_time_limit(120000);
ini_set('memory_limit','2048M');

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


Route::get('/run','RunController@onController')->name('run');

Route::get('/info',function(){
	Cache::put('test','var');
	return Cache::get('test');
});
<?php

#use Illuminate\Http\Request;
#use App\Http\Requests\LoginRequest;
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


Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api'
  ], function () {

      Route::post('/auth/register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register'
      ]);

      Route::post('/auth/login', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login'
      ]);

});
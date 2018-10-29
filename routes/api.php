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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\AuthController@login');

Route::middleware('jwt.auth')->group(function(){
    Route::get('logout', 'API\AuthController@logout');
    Route::get('tasks/show', 'TaskController@index');
    Route::post('task/create', 'TaskController@store');
    Route::get('task/{task}', 'TaskController@show');
    Route::delete('task/delete/{task}', 'TaskController@destroy');
    Route::patch('task/update/{task}', 'TaskController@update');
});
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

Route::get('projects', 'ApiController@projects')->middleware("auth:api");
Route::get('surveys/{project}', 'ApiController@surveys')->middleware("auth:api");
Route::apiResource('media', 'MediaController');

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', 'UserController@login');
    Route::get('/logout', 'UsesController@logout')->middleware('auth:api');
});

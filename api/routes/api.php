<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/models/{id}/texts', 'TextsController@index');
Route::get('/models', 'ModelsController@index');
Route::post('/models', 'ModelsController@create');
Route::put('/models/{id}', 'ModelsController@edit');
Route::delete('/models/{id}', 'ModelsController@destroy');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

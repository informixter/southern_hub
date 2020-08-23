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

Route::get('/models/{model_id}/texts', 'TextsController@index');
Route::post('/models/search', 'ModelsController@search');
Route::get('/models/search', 'ModelsController@search2');
Route::post('/models/{model_id}/texts', 'TextsController@addText');
Route::get('/texts/{textId}', 'TextsController@byId');
Route::put('/texts/{textId}', 'TextsController@editText');
Route::post('/texts/{textId}/labels', 'TextsController@saveLabels');
Route::get('/models', 'ModelsController@index');
Route::post('/models', 'ModelsController@create');
Route::put('/models/{model_id}', 'ModelsController@edit');
Route::delete('/models/{modelid}', 'ModelsController@destroy');


Route::get('/labels/{model_id}', 'LabelsController@index');
Route::post('/labels/{model_id}', 'LabelsController@create');
Route::post('/labels/{model_id}/{label_id}', 'LabelsController@edit');
Route::delete('/labels/{id}', 'LabelsController@destroy');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

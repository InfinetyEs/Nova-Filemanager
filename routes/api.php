<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
 */
Route::get('data', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@getData');
Route::get('{resource}/{attribute}/data', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@getDataField');
Route::post('actions/create-folder', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@createFolder');
Route::post('actions/delete-folder', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@deleteFolder');
Route::post('actions/get-info', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@getInfo');
Route::post('actions/remove-file', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@removeFile');

Route::post('uploads/add', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@upload');
Route::get('uploads/update', \Infinety\Filemanager\Http\Controllers\FilemanagerToolController::class.'@updateFile');

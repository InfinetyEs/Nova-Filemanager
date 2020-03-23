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
Route::get('data', FilemanagerToolController::class.'@getData');
Route::get('{resource}/{attribute}/data', FilemanagerToolController::class.'@getDataField');
Route::post('actions/move', FilemanagerToolController::class.'@move');
Route::post('actions/create-folder', FilemanagerToolController::class.'@createFolder');
Route::post('actions/delete-folder', FilemanagerToolController::class.'@deleteFolder');
Route::post('actions/get-info', FilemanagerToolController::class.'@getInfo');
Route::post('actions/remove-file', FilemanagerToolController::class.'@removeFile');
Route::post('actions/rename-file', FilemanagerToolController::class.'@renameFile');
Route::get('actions/download-file', FilemanagerToolController::class.'@downloadFile');
Route::post('actions/rename', FilemanagerToolController::class.'@rename');

Route::post('events/folder', FilemanagerToolController::class.'@folderUploadedEvent');

Route::post('uploads/add', FilemanagerToolController::class.'@upload');
Route::get('uploads/update', FilemanagerToolController::class.'@updateFile');

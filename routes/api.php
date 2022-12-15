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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('user')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class,'index'])->middleware('auth:sanctum');
    Route::get('{id}', [\App\Http\Controllers\UserController::class,'getInCard'])->middleware('auth:sanctum');
    Route::get('{id}/get-all', [\App\Http\Controllers\UserController::class,'getAll'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\UserController::class,'update'])->middleware('auth:sanctum');
    Route::put('password', [\App\Http\Controllers\UserController::class,'updatePassword'])->middleware('auth:sanctum');
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::prefix('auth')->group(function () {
    Route::post('register', [\App\Http\Controllers\UserController::class,'register']);
    Route::post('login', [\App\Http\Controllers\UserController::class,'login']);
    Route::post('logout', [\App\Http\Controllers\UserController::class,'logout'])->middleware('auth:sanctum');
});

Route::prefix('boards')->group(function () {
    Route::get('/', [\App\Http\Controllers\JobBoardController::class,'index'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\JobBoardController::class,'store'])->middleware('auth:sanctum');
    Route::post('{id}', [\App\Http\Controllers\JobBoardController::class,'update'])->middleware('auth:sanctum');
    Route::post('{id}/attach', [\App\Http\Controllers\JobBoardController::class,'attachUser'])->middleware('auth:sanctum');
    Route::delete('{id}/detach', [\App\Http\Controllers\JobBoardController::class,'detachUser'])->middleware('auth:sanctum');
    Route::get('{id}/user', [\App\Http\Controllers\JobBoardController::class,'getUser'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\JobBoardController::class,'destroy'])->middleware('auth:sanctum');
});

Route::prefix('directories')->group(function () {
    Route::get('{id}', [\App\Http\Controllers\DirectoryController::class,'index'])->middleware('auth:sanctum');
    Route::post('{id}', [\App\Http\Controllers\DirectoryController::class,'store'])->middleware('auth:sanctum');
    Route::put('{id}', [\App\Http\Controllers\DirectoryController::class,'update'])->middleware('auth:sanctum');
    Route::put('{id}/index', [\App\Http\Controllers\DirectoryController::class,'updateIndex'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\DirectoryController::class,'destroy'])->middleware('auth:sanctum');
});

Route::prefix('cards')->group(function () {
    Route::get('{id}', [\App\Http\Controllers\CardController::class,'index'])->middleware('auth:sanctum');
    Route::post('{id}', [\App\Http\Controllers\CardController::class,'store'])->middleware('auth:sanctum');
    Route::put('{id}', [\App\Http\Controllers\CardController::class,'update'])->middleware('auth:sanctum');
    Route::put('{id}/index', [\App\Http\Controllers\CardController::class,'updateIndex'])->middleware('auth:sanctum');
    Route::put('{id}/directory', [\App\Http\Controllers\CardController::class,'updateDirectory'])->middleware('auth:sanctum');
    Route::put('{id}/change-status', [\App\Http\Controllers\CardController::class,'updateStatus'])->middleware('auth:sanctum');
    Route::put('{id}/change-status-deadline', [\App\Http\Controllers\CardController::class,'updateDeadline'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\CardController::class,'destroy'])->middleware('auth:sanctum');

    //user
    Route::post('{id}/attach-users', [\App\Http\Controllers\CardController::class,'attachUser'])->middleware('auth:sanctum');
    Route::delete('{id}/detach-users', [\App\Http\Controllers\CardController::class,'detachUser'])->middleware('auth:sanctum');

    //label
    Route::post('{id}/label', [\App\Http\Controllers\LabelController::class,'store'])->middleware('auth:sanctum');
    Route::post('{id}/attach-labels', [\App\Http\Controllers\LabelController::class,'storeAttach'])->middleware('auth:sanctum');
    Route::delete('{id}/detach-labels', [\App\Http\Controllers\LabelController::class,'detach'])->middleware('auth:sanctum');

    //file
    Route::post('{id}/upload-file', [\App\Http\Controllers\FileController::class,'store'])->middleware('auth:sanctum');
});

Route::prefix('labels')->group(function () {
    Route::get('/', [\App\Http\Controllers\LabelController::class,'index'])->middleware('auth:sanctum');
    Route::get('{id}', [\App\Http\Controllers\LabelController::class,'getInCard'])->middleware('auth:sanctum');
    Route::put('{id}', [\App\Http\Controllers\LabelController::class,'update'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\LabelController::class,'destroy'])->middleware('auth:sanctum');
});

Route::prefix('files')->group(function () {
    Route::get('{id}', [\App\Http\Controllers\FileController::class,'index'])->middleware('auth:sanctum');
    Route::put('{id}', [\App\Http\Controllers\FileController::class,'update'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\FileController::class,'destroy'])->middleware('auth:sanctum');
});

Route::prefix('job-lists')->group(function () {
    Route::get('{id}', [\App\Http\Controllers\JobListController::class,'index'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\JobListController::class,'store'])->middleware('auth:sanctum');
    Route::put('{id}', [\App\Http\Controllers\JobListController::class,'update'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\JobListController::class,'destroy'])->middleware('auth:sanctum');
});

Route::prefix('job-list-child')->group(function () {
    Route::get('{id}', [\App\Http\Controllers\JobListChildController::class,'index'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\JobListChildController::class,'store'])->middleware('auth:sanctum');
    Route::put('{id}', [\App\Http\Controllers\JobListChildController::class,'update'])->middleware('auth:sanctum');
    Route::put('{id}/change-status', [\App\Http\Controllers\JobListChildController::class,'updateStatus'])->middleware('auth:sanctum');
    Route::delete('{id}', [\App\Http\Controllers\JobListChildController::class,'destroy'])->middleware('auth:sanctum');
});

Route::prefix('filter')->group(function () {
    Route::get('/', [\App\Http\Controllers\DirectoryController::class,'filter'])->middleware('auth:sanctum');
});

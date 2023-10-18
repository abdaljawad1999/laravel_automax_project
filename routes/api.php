<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api;
use App\Http\Controllers\api\PostsController;
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
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/posts',[PostsController::class,'index']);
Route::get('/posts/{post}',[PostsController::class,'show']);
Route::get('/posts/search/{name}',[PostsController::class,'search']);
// Route::resource('posts',PostsController::class);
Route::group(['middleware'=>['auth:sanctum']], function () {
   
    Route::post('/posts',[PostsController::class,'store']);
    Route::put('/posts/{post}',[PostsController::class,'update']);
    Route::delete('/posts/{post}',[PostsController::class,'destroy']);
    Route::post('/logout',[AuthController::class,'logout']);


});
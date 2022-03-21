<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::get('profile', [UserController::class, 'profile']);

Route::get('show', [UserController::class, 'index']);

Route::patch('update', [UserController::class, 'update']);
Route::patch('update-user', [UserController::class, 'updateUser']);

Route::get('my-post', [PostController::class, 'userPosts']);

Route::apiResource('post', PostController::class);

Route::post('post/{post}/comment', [CommentController::class, 'store']);

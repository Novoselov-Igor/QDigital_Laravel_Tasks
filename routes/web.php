<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserProfileController::class, 'index']);
Route::get('/profile/{id}', [UserProfileController::class, 'showUserProfile'])->name('profiles.show');

Route::get('/comments', [CommentController::class, 'showComments'])->name('comments.show');
Route::post('/comments/add', [CommentController::class, 'addComment'])->name('comments.add');
Route::post('/comments/delete', [CommentController::class, 'deleteComment'])->name('comments.delete');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

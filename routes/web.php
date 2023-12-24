<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LibraryController;
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
Route::get('/profile/{id}', [UserProfileController::class, 'show'])->name('profiles.show');

Route::get('/comments', [CommentController::class, 'show'])->name('comments.show');
Route::post('/comments/add', [CommentController::class, 'add'])->name('comments.add');
Route::post('/comments/delete', [CommentController::class, 'delete'])->name('comments.delete');

Route::post('/library/giveAcess', [LibraryController::class, 'giveAccess'])->name('library.giveAccess');
Route::post('/library/removeAcess', [LibraryController::class, 'removeAccess'])->name('library.removeAccess');

Route::middleware('verify.library.access')->group(function () {
    Route::get('/library/author/{authorId}', [LibraryController::class, 'index'])->name('library.goto');

    Route::get('/library/author/{authorId}/book/{bookId}', [BookController::class, 'index'])->name('book.goto');

    Route::post('/library/author/{authorId}/book/show', [BookController::class, 'show'])->name('book.show');
    Route::post('/library/author/{authorId}/book/add', [BookController::class, 'add'])->name('book.add');
    Route::post('/library/author/{authorId}/book/change', [BookController::class, 'change'])->name('book.change');
    Route::post('/library/author/{authorId}/book/delete', [BookController::class, 'delete'])->name('book.delete');

    Route::post('/library/author/{authorId}/book/linkAccess/give', [BookController::class, 'giveLinkAccess'])->name('book.giveLinkAccess');
    Route::post('/library/author/{authorId}/book/linkAccess/remove', [BookController::class, 'removeLinkAccess'])->name('book.removeLinkAccess');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

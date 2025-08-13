<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BookController;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Verify;
use App\Livewire\Books\Index as BooksIndex;
use App\Livewire\Books\Show as BooksShow;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

// Route::view('/', 'welcome')->name('home');
Route::get('/', Welcome::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', Verify::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    Route::get('password/confirm', Confirm::class)
        ->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('notes/{note}', App\Livewire\NoteChat::class)->name('notes.chat');
    Route::get('upload', App\Livewire\NoteUploader::class)->name('notes.upload');

    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('logout', LogoutController::class)
        ->middleware('auth')
        ->name('logout');

    // Route::middleware(['auth'])->group(function () {
    // Livewire components for book management
    Route::get('/books', \App\Livewire\Books\Index::class)->name('books.index');
    Route::get('/books/create', \App\Livewire\Books\Form::class)->name('books.create');
    Route::get('/books/{book}', \App\Livewire\Books\Show::class)->name('books.show');
    Route::get('/books/{book}/edit', \App\Livewire\Books\Form::class)->name('books.edit');

    // Controller methods for actions
    // Route::post('/books', [\App\Http\Controllers\BookController::class, 'store'])
    //     ->name('books.store');
    // Route::put('/books/{book}', [\App\Http\Controllers\BookController::class, 'update'])
    //     ->name('books.update');
    // Route::delete('/books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])
    //     ->name('books.destroy');
    // Route::post('/books/{book}/add-to-library', [\App\Http\Controllers\BookController::class, 'addToLibrary'])
    //     ->name('books.add-to-library');
    // });
});

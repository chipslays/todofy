<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// todo 
Route::get('/', '\App\Http\Controllers\TodoController@index');
Route::get('/new', '\App\Http\Controllers\TodoController@new')->middleware('auth')->name('new_todo');
Route::post('/new', '\App\Http\Controllers\TodoController@create')->middleware('auth');
Route::get('/edit/{id}', '\App\Http\Controllers\TodoController@edit')->middleware('auth');
Route::post('/edit/{id}', '\App\Http\Controllers\TodoController@update')->middleware('auth');

// note manage buttons
Route::get('/note/{method}/{id}', '\App\Http\Controllers\NoteController@index');

// public notes
Route::get('/@{username}/{code}', '\App\Http\Controllers\TodoController@showUserDetailPublicNote');
Route::get('/@{username}', '\App\Http\Controllers\TodoController@showUserPublicNotes')->name('user_notes');

// categories
Route::get('/category', '\App\Http\Controllers\CategoryController@edit')->name('category');
Route::post('/category/delete/{id}', '\App\Http\Controllers\CategoryController@delete');
Route::post('/category/add', '\App\Http\Controllers\CategoryController@add');
Route::post('/category/update', '\App\Http\Controllers\CategoryController@update');

Route::get('/test', function () {
    $user = User::find(1);
    dd($user->notes);
});

Auth::routes();





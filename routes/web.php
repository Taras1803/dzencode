<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CommentController@index');
Route::post('addComment', 'CommentController@addComment');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

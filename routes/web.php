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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('','PostController@index')->name('posts.index');
Route::get('post/all','PostController@getAll')->name('posts.getAll');
Route::get('post','PostController@index')->name('posts.index');
Route::post('post','PostController@store')->name('posts.store');
Route::patch('post/{id}','PostController@update')->name('posts.update');
Route::delete('post/{id}','PostController@destroy')->name('posts.destroy');
Route::get('post/{id}/restore','PostController@restore')->name('posts.restore');
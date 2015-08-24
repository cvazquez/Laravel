<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('hello', function () {
	return "hello world";
});

Route::get('/', 'Blog\BlogController@home');

Route::get('/categories', 'Blog\BlogController@categories');

Route::get('{titleURL}', 'Blog\BlogController@entry');

Route::get('/category/{categoryURL}', 'Blog\BlogController@categoryEntries');
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


#Registration
Route::get('/register', [
    'as' => 'register',
    'uses' => 'RegistrationController@create'
]);


#Session
Route::any('/login',array (
    'as' => 'login',
    'uses' => 'LoginController@login'
));
Route::get('/logout', array (
    'as' => 'logout',
    'uses' => 'LoginController@logout'
));
Route::any('/dashboard', array (
    'as' => 'dashboard',
    'uses' => 'HomeController@dashboard'
));


#Pages
Route::get('/', [
    'as' => 'home',
    'uses' => 'HomeController@create'
]);
Route::get('/about', [
    'as' => 'about',
    'uses' => 'HomeController@about'
]);
Route::get('/guest', [
    'as' => 'guest',
    'uses' => 'HomeController@guest'
]);
Route::get('/host', [
    'as' => 'host',
    'uses' => 'HomeController@host'
]);
Route::get('/test', [
    'as' => 'test',
    'uses' => 'YoutubeController@index'
]);


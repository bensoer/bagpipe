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
Route::any('/register', [
    'as' => 'register',
    'uses' => 'RegistrationController@register'
]);
Route::any('/register/confirm/{confirmationCode}', array (
    'as' => 'confirm',
    'uses' => 'RegistrationController@confirm'
));


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
Route::any('/guest', [
    'as' => 'guest',
    'uses' => 'HomeController@findParty'
]);
Route::get('/host', [
    'as' => 'host',
    'uses' => 'HomeController@host'
]);

/*
Route::get('/test', [
    'as' => 'test',
    'uses' => 'AlphaController@index'
]);
Route::any('/wut', [
    'as' => 'search' ,
    'uses' => 'AlphaController@search'
]);
Route::any('/playlist', [
    'as' => 'playlist',
    'uses' => 'AlphaController@playlist'
]);
*/

#API

Route::any('/api/search',[
    'as' => 'searchSong',
    'uses' => 'YoutubeController@AJAXSearch'
]);
Route::post('/api/playlist/add', [
    'as' => 'addSongs',
    'uses' => 'SongController@AddSongs'
]);
Route::post('/api/playlist/delete', [
    'as' => 'unloadDBSession',
    'uses' => 'SessionController@UnloadDBSession'
]);
Route::post('/api/playlist/update/current', [
    'as' => 'updateCurrent',
    'uses' => 'SessionController@UpdateCurrentSong'
]);
Route::post('/api/playlist', [
    'as' => 'getArrays',
    'uses' => 'SessionController@GetArrays'
]);
Route::post('/api/playlist/vote', [
    'as' => 'submitVote',
    'uses' => 'SongController@VoteSong'
]);
Route::post('/api/playlist/song/delete' , [
    'as' => 'deleteSong',
    'uses' => 'SongController@DeleteSong'
]);
Route::post('/api/playlist/delete/guest', [
    'as' => 'decrementGuest',
    'uses' => 'AnalyticsController@DecrementGuest'
]);
Route::post('/api/playlist/update/current/time', [
    'as' => 'setTime',
    'uses' => 'SessionController@UpdateTime'
]);
Route::post('/api/playlist/double', [
    'as' => 'toggleDoublePlaylist',
    'uses' => 'SessionController@ToggleDoublePlaylist'
]);


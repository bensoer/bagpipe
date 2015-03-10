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


Route::get('/test', [
    'as' => 'test',
    'uses' => 'YoutubeController@index'
]);
Route::any('/wut', [
    'as' => 'search' ,
    'uses' => 'YoutubeController@search'
]);
Route::any('/playlist', [
    'as' => 'playlist',
    'uses' => 'YoutubeController@playlist'
]);


#API

Route::any('/searchSong',[
    'as' => 'searchSong',
    'uses' => 'YoutubeController@AJAXSearch'
]);
Route::any('/addToPlaylist', [
    'as' => 'updateSongs',
    'uses' => 'YoutubeController@AJAXAddSongs'
]);
Route::any('/unloadDBSession', [
    'as' => 'unloadDBSession',
    'uses' => 'YoutubeController@AJAXUnloadDBSession'
]);
Route::any('/updateCurrent', [
    'as' => 'updateCuurent',
    'uses' => 'YoutubeController@AJAXUpdateCurrentSong'
]);
Route::any('/getCurrent', [
    'as' => 'getCurrent',
    'uses' => 'YoutubeController@AJAXGetCurrentSong'
]);
Route::any('/getUpNext', [
    'as' => 'getUpNext',
    'uses' => 'YoutubeController@AJAXGetUpNextSongs'
]);
Route::any('/getArrays', [
    'as' => 'getArrays',
    'uses' => 'YoutubeController@AJAXGetArrays'
]);
Route::any('/submitVote', [
    'as' => 'submitVote',
    'uses' => 'YoutubeController@AJAXSubmitVote'
]);
Route::any('/deleteSong' , [
    'as' => 'deleteSong',
    'uses' => 'YoutubeController@AJAXDeleteSong'
]);

Route::any('/sync', [
    'as' => 'synchronization',
    'uses' => 'YoutubeController@AJAXSync'
]);

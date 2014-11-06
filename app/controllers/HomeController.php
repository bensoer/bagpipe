<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

    public function create()
    {
        return View::make('pages.index');
    }

    public function guest()
    {
        return View::make('party.guest');
    }

    public function host()
    {
        return View::make('party.host');
    }

    public function about()
    {
        return View::make('pages.about');
    }

	public function showWelcome()
	{
		return View::make('hello');
	}

    public function dashboard(){

        return View::make('dashboard');
    }

}

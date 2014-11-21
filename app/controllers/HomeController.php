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

    const API_KEY = 'AIzaSyCQqOHmCw-hNYt6q3pwmjVj_IEz0c_aJCc';
    var $youtube;


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
       //$user = new User();
        $token = str_random(10);
        //$user->session_token = $token;
        //$user->save();

        $playlist = $this->playlist();
        return View::make('party.host')->with('data', array( 'shareCode' => $token, "videoIDs" => $playlist['videoIDs'], "videoNames" => $playlist['videoNames']));
    }


    public function search(){
        $search = Input::get('search');

        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));
        $searchResult = $this->youtube->searchVideos($search);

        $embedded = array();
        foreach($searchResult as $search){
            $videoId = $search->id->videoId;
            $video = $this->youtube->getVideoInfo($videoId);

            $embedded[] = $video->player->embedHtml . "</iframe>";
        }


        return View::make('party.search')->with('embedded', $embedded);

        //print_r($searchResult);



    }

    public function playlist(){
        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));

        $videoIDs = array("H_HUasB6DPQ","7hHX3tCti74", "Ou1fTw7iMjA");
        $videoNames = array();
        foreach($videoIDs as $id){
            $videoNames[] =  $this->youtube->getVideoInfo($id)->snippet->title;

        }

        return array("videoIDs" => $videoIDs, "videoNames" => $videoNames);
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

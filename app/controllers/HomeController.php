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

    public function findParty(){

        if($this->isAPostRequest()){
            $token = Input::get('party_search');

            //avoiding injection
            if($token === strip_tags($token)){

                $results = DB::table('user')->where('session_token', $token)->first();

                if(empty($results)){
                    return Redirect::back()
                        ->with('error','Sorry, this host does not exist or has closed the session');
                }else{

                    //get whole songlist
                    $songlist = DB::table('songlist')->where('session_token',$token)->orderBy(DB::raw('ABS(priority)'), 'asc')->get();

                    //get the user and find what is the currently playing song
                    $user = DB::table('user')->where('session_token', $token)->first();
                    $currentlyPlaying = $user->currently_playing;

                    //only send the view the list from the currently playing song onward
                    $displayablesonglist = Array();
                    for($i = $currentlyPlaying ; $i < count($songlist) ; $i++){
                        $displayablesonglist[] = $songlist[$i];
                    }

                    //var_dump($displayablesonglist);
                   return View::make('party.guest')->with('songlist', $displayablesonglist);
                }



            }else{
                return Redirect::back()
                    ->with('error', 'Invalid Token Submitted');
            }



        }else{
            return View::make('party.findparty');
        }

    }

    public function guest()
    {
        return View::make('party.guest');
    }

    public function host()
    {
       $user = new User();
        $token = str_random(10);
        $user->session_token = $token;
        $user->save();

        //Session::put("token", $token);

        $playlist = $this->playlist();
        return View::make('party.host')->with('data', array( 'shareCode' => $token));//, "videoIDs" => $playlist['videoIDs'], "videoNames" => $playlist['videoNames']));
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

    private function isAPostRequest(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            return true;
        }else{
            return false;
        }
    }

}

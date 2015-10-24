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

                $results = User::where('session_token', $token)->first();

                if(empty($results)){
                    return Redirect::back()
                        ->with('error','Sorry, this host does not exist or has closed the session');
                }else{

                    //get whole songlist
                    $songlist = Song::where('session_token',$token)->orderBy(DB::raw('ABS(priority)'), 'asc')->get();

                    //if there are no songs, then guest shouldn't be allowed in as the playlist isn't ready
                    if(empty($songlist)){
                        return Redirect::back()
                            ->with('error', "Sorry, but the playlist you are looking for isn't ready yet");
                    }


                    //get the user and find what is the currently playing song
                    $user = User::where('session_token', $token)->first();
                    $currentlyPlaying = $user->currently_playing;

                    //only send the view the list from the currently playing song onward
                    $displayablesonglist = Array();
                    for($i = $currentlyPlaying ; $i < count($songlist) ; $i++){
                        $displayablesonglist[] = $songlist[$i];
                    }

                    //add this to number fo guests
                    $user->increment('guests');
                    $user->save();

                    //var_dump($displayablesonglist);
                   return View::make('party.guest')->with('data', array('songlist' => $displayablesonglist, 'token' => $token));
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
        $token = strtoupper(str_random(10));
        $user->session_token = $token;
        $user->save();

        //Session::put("token", $token);

        return View::make('party.host')->with('data', array( 'shareCode' => $token));//, "videoIDs" => $playlist['videoIDs'], "videoNames" => $playlist['videoNames']));
    }


    public function about()
    {
        return View::make('pages.about');
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

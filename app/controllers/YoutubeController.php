<?php

class YoutubeController extends BaseController {

    const API_KEY = 'AIzaSyCQqOHmCw-hNYt6q3pwmjVj_IEz0c_aJCc';
    var $youtube;

    /* --- DEPRECATED --- */
    public function index()
    {
        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));

        $vID = 'rie-hPVJ7Sw';
        var_dump( $this->youtube->getVideoInfo($vID)->snippet->title );
    }

    /* --- DEPRECATED --- */
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

    /* --- DEPRECATED --- */
    public function playlist(){
        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));

        $videoIDs = array("H_HUasB6DPQ","7hHX3tCti74", "Ou1fTw7iMjA");
        $videoNames = array();
        foreach($videoIDs as $id){
            $videoNames[] =  $this->youtube->getVideoInfo($id)->snippet->title;

        }

        return View::make('party.playlist')->with("videoData", array("videoIDs" => $videoIDs, "videoNames" => $videoNames));
    }

    private function getYoutubeInstance(){
        return new Madcoda\Youtube(array('key' => self::API_KEY));
    }

    public function AJAXSearch(){
        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);

        $search = $formFields['search'];

        $results = $this->searchForVideos($search);

        return Response::json(array(
            'success' => true,
            'data' => $results
        ));
    }

    private function searchForVideos($search){
        //$this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));
        $youtube = $this->getYoutubeInstance();
        //$searchResult = $this->youtube->searchVideos($search);
        $searchResult = $youtube->searchVideos($search);

        $videos = array(array());
        foreach($searchResult as $search){
            $videoId = $search->id->videoId;
            //$video = $this->youtube->getVideoInfo($videoId);
            $video = $youtube->getVideoInfo($videoId);

            $videos[0][] = $video->player->embedHtml . "</iframe>";
            $videos[1][] = $video->snippet->title;
            $videos[3][] = $videoId;

        }

        return $videos;
    }

    public function AJAXUpdateCurrentSong(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        DB::table('user')
            ->where('session_token', $json->session_token)
            ->update(array('currently_playing' => $json->currently_playing));

        return Response::json(array(
            'success' => true,
            'data' => $inputData
        ));


    }

    public function AJAXGetCurrentSong(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

       $user = DB::table('user')->where('session_token', $json->session_token)->first();
       $songData = DB::table('songlist')->where(array('session_token' => $json->session_token, 'priority' => $user->currently_playing))->first();

        /*$songData = DB::table('user')
            ->join("songlist","user.session_token", "=", "songlist.session_token" )
            ->join("songlist", "user.currently_playing", "=", "songlist.priority")
            ->select("songlist.songid", "songlist.songname")
            ->get();
*/
        if(empty($user)){
            return Response::json(array(
                "success" => false
            ));
        }

        return Response::json(array(
            'success' => true,
            'id' => $songData->songid,
            'name' => $songData->songname
        ));
    }

    public function AJAXGetUpNextSongs(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $user = DB::table('user')->where('session_token', $json->session_token)->first();
        $songData = DB::table('songlist')->select('songname','songid','votes')->where(array('session_token' => $json->session_token))->orderBy(DB::raw('ABS(priority)'), 'asc')->get();

        /*$songData = DB::table('user')
            ->join("songlist","user.session_token","=", "songlist.session_token")
            ->where(array('session_token' => $json->session_token))
            ->orderBy(DB::raw('ABS(priority)'), 'asc')
            ->select("songlist.songname","songlist.songid","songlist.votes")
            ->get();
*/
        if(empty($user)){
            return Response::json(array(
                "success" => false,
            ));
        }

        $array = Array(Array());
        for($i = $user->currently_playing+1 ; $i < count($songData); $i++){
            $array[0][] = $songData[$i]->songname;
            $array[1][] = $songData[$i]->songid;
            $array[2][] = $songData[$i]->votes;
        }

        $returnData = json_encode($array);

        return Response::json($returnData);



    }

    public function AJAXSubmitVote(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $user = DB::table('user')->where('session_token', $json->session_token)->first();
        $currently_playing = $user->currently_playing;

        $votes = DB::table('songlist')->select('votes')->where(array('session_token' => $json->session_token, 'songid' => $json->videoid))->get();
        DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $json->videoid))->update(array('votes' => $votes[0]->votes+1));


        //$songData = DB::table('songlist')->select('songname','songid','votes')->where(array('session_token' => $json->session_token))->orderBy(DB::raw('ABS(priority)'), 'asc')->get();

        $newOrder = $this->reprioritize($json->session_token, $currently_playing);

        //return Response::json(json_encode($newOrder));
        $count = 0;
        foreach($newOrder as $songid){
            DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $songid))->update(array('priority'=> $count));
            $count++;
        }



/*
        //get the priority of the song that was voted on
        $votedPriority = DB::table('songlist')->select('priority')->where(array('session_token' => $json->session_token, 'songid' => $json->videoid))->get();

        //get the songid and priority to the song that has the priority above

        //return Response::json(json_encode($votedPriority));
        $nextAbove = DB::table('songlist')->select('songid', 'priority')->where(array('session_token' => $json->session_token, 'priority' => $votedPriority[0]->priority-1))->get();



        if($nextAbove[0]->priority != $currently_playing){
            //increment the one aboves priority thus breinging it lower in the list

            $priority1 = DB::table('songlist')->select('priority')->where(array('session_token' => $json->session_token, 'songid' => $nextAbove[0]->songid))->get();
            DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $nextAbove[0]->songid))->update(array('priority' => $priority1[0]->priority+1));

            //decrement the votes for song priority thus bringing it higher in the list

            $priority2 = DB::table('songlist')->select('priority')->where(array('session_token' => $json->session_token, 'songid' => $json->videoid))->get();
            DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $json->videoid))->update(array('priority' => $priority2[0]->priority-1));
        }
*/
    }

    private function reprioritize($session_token, $currently_playing){

        $results = DB::table('songlist')
            ->select('songid', 'priority', 'votes')
            ->where('session_token', $session_token)
            ->orderBy(DB::raw('ABS(priority)'), 'asc')
            ->get();

        $newOrder = Array();

        for($j = $currently_playing+1; $j < count($results)-1 ; $j++) {
            for ($i = $currently_playing + 2; $i < count($results); $i++) {
                //if current has more votes then the previous
                if ($results[$i - 1]->votes < $results[$i]->votes) {
                    //swap
                    $temp = $results[$i];
                    $results[$i] = $results[$i - 1];
                    $results[$i - 1] = $temp;

                }
            }
        }

        foreach($results as $row){
            $newOrder[] = $row->songid;
        }

        return $newOrder;

    }

    public function AJAXDeleteSong(){
        $inputData = Input::get("formData");
        $json = json_decode($inputData);

        DB::table('songlist')->where(array("session_token" => $json->session_token, "songid" => $json->songid))->delete();

        $user = DB::table('user')->select("currently_playing")->where("session_token", $json->session_token)->get();

        $newOrder = $this->reprioritize($json->session_token, $user[0]->currently_playing);

        $count = 0;
        foreach($newOrder as $songid){
            DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $songid))->update(array('priority'=> $count));
            $count++;
        }

        return Response::json(array(
            "success" => true,
        ));

    }

    public function AJAXAddSongs(){
        $youtube = $this->getYoutubeInstance();
        $inputData = Input::get('formData');
        //parse_str($inputData, $formFields);
       $json =  json_decode($inputData);

        //need to have order maintained in database. belongs to token in last position

        $length = count($json); //how many to add
        $sessionToken = $json[$length-1]; //grab the token from the last one

        //find in database how many songs are there already with this session token
        $numOfSongs = DB::table('songlist')->where('session_token', $sessionToken)->count();

        for($i=0 ; $i < $length-1 ; $i++){ //grab all except the last token one

            $video = $youtube->getVideoInfo($json[$i]);
            $title = $video->snippet->title;


            DB::table('songlist')->insert(
                array( "session_token" => $sessionToken, "songid" => $json[$i], "songname" => $title, "priority" => ($numOfSongs +$i))
            );
        }



        return Response::json(array(
            'success' => true,
            'data' => $inputData
        ));
    }

    public function AJAXGetArrays(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $songData = DB::table('songlist')->select('songname','songid')->where(array('session_token' => $json->session_token))->orderBy(DB::raw('ABS(priority)'), 'asc')->get();

        if(empty($songData)){
            return Response::json(array(
                "success" => false
            ));
        }

        $json = json_encode($songData);

        return Response::json($json);
    }

    public function AJAXUnloadDBSession(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        DB::table('songlist')->where('session_token','=', $json->session_token)->delete();
        DB::table('user')->where('session_token','=', $json->session_token)->delete();

        return Response::json(array(
            'data' => $json
        ));

    }


}
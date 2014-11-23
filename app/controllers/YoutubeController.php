<?php

class YoutubeController extends BaseController {

    const API_KEY = 'AIzaSyCQqOHmCw-hNYt6q3pwmjVj_IEz0c_aJCc';
    var $youtube;

    public function index()
    {
        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));

        $vID = 'rie-hPVJ7Sw';
        var_dump( $this->youtube->getVideoInfo($vID)->snippet->title );
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

        return View::make('party.playlist')->with("videoData", array("videoIDs" => $videoIDs, "videoNames" => $videoNames));
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
        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));
        $searchResult = $this->youtube->searchVideos($search);

        $videos = array(array());
        foreach($searchResult as $search){
            $videoId = $search->id->videoId;
            $video = $this->youtube->getVideoInfo($videoId);

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
            ->update(array('currently_playing' => $json->currently_playing ));

        return Response::json(array(
            'success' => true,
            'data' => $inputData
        ));


    }

    public function AJAXAddSongs(){
        $this->youtube = new Madcoda\Youtube(array( 'key' => self::API_KEY ));
        $inputData = Input::get('formData');
        //parse_str($inputData, $formFields);
       $json =  json_decode($inputData);

        //need to have order maintained in database. belongs to token in last position

        $length = count($json);
        $sessionToken = $json[$length-1];

        for($i=0 ; $i < $length-1 ; $i++){

            $video = $this->youtube->getVideoInfo($json[$i]);
            $title = $video->snippet->title;


            DB::table('songlist')->insert(
                array( "session_token" => $sessionToken, "songid" => $json[$i], "songname" => $title, "priority" => $i+1)
            );
        }



        return Response::json(array(
            'success' => true,
            'data' => $inputData
        ));
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
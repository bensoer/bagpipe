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
            echo $this->youtube->getVideoInfo($id)->snippet->title;
        }

        return View::make('party.playlist')->with("videoIDs", $videoIDs);
    }


}
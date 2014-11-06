<?php

class YoutubeController extends BaseController {

    var $youtube;

    public function index()
    {
        $API_KEY = 'AIzaSyCQqOHmCw-hNYt6q3pwmjVj_IEz0c_aJCc';
        $this->youtube = new Madcoda\Youtube(array( 'key' => $API_KEY ));

        $vID = 'rie-hPVJ7Sw';
        var_dump( $this->youtube->getVideoInfo($vID) );
    }

}
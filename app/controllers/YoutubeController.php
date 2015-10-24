<?php

class YoutubeController extends BaseController {

    const API_KEY = 'AIzaSyCQqOHmCw-hNYt6q3pwmjVj_IEz0c_aJCc';
    var $youtube;

    /** helper function that manages the Youtube API instance through the Madcoda library
     * @return \Madcoda\Youtube
     */
    private function getYoutubeInstance(){
        return new Madcoda\Youtube(array('key' => self::API_KEY));
    }



    /** search route submitting user search criteria and returning result data
     * @return mixed
     */
    public function AJAXSearch(){

        $input = $this->parseJsonInput();

        $search = $input->search;

        $results = $this->searchForVideos($search);
        //$results = 0;

        return Response::json(array(
            'success' => true,
            'data' => $results,
            'other' => $input
        ));
    }

    /** search functionality for searching songs on the youtube api by the client
     * @param $search the search word content
     * @return array the results from the search
     */
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

//MOVED TO SESSION CONTROLLER
    /** updates the current song for the specified session. This is to keep other clients up to date with the currently
     * running song and to enforce validation if they are behind in updating before making a change (validation is
     * not yet implemented)
     * @return mixed
     */
    public function AJAXUpdateCurrentSong(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);


        User::where('session_token', $json->session_token)
            ->update(array('currently_playing' => $json->currently_playing));


        return Response::json(array(
            'success' => true,
            'data' => $inputData
        ));


    }

//MOVED TO SONG CONTROLLER
    /** submits a vote for a song, changing the order of the playlist based on the newly added vote. The ordering is
     * sorted on the client side and then passed to the server to implement and update all other clients
     * @return mixed
     */
    public function AJAXSubmitVote(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        Song::where(array('session_token' => $json->session_token, 'songid' => $json->song_id))->update(array('votes' => $json->vote_count));

        $newOrder = $json->new_song_order;

        //update the priority of the playlist
        $count = 0;
        foreach($newOrder as $songObj){
            Song::where(array('session_token' => $json->session_token, 'songid' => $songObj->id))->update(array('priority'=> $count));
            $count++;
        }

        return Response::json(array(
            'success' => true,
            'session_token' => $json->session_token,
            'song_id' => $json->song_id,
            'song_name' => $json->song_name,
            'updated_vote' => $json->vote_count
        ));
    }

 //MOVED TO SONG CONTROLLER
    /** deletes the passed song from the playlist belonging to the specified session
     * @return mixed
     */
    public function AJAXDeleteSong(){
        $inputData = Input::get("formData");
        $json = json_decode($inputData);

        Song::where(array("session_token" => $json->session_token, "songid" => $json->deleted_song_id))->delete();

        $newOrder = $json->new_song_order;

        $count = 0;
        foreach($newOrder as $songObj){
            Song::where(array('session_token' => $json->session_token, 'songid' => $songObj->id))->update(array('priority'=> $count));
            $count++;
        }

        return Response::json(array(
            "success" => true,
            "session_token" => $json->session_token,
            "deleted_song_id" => $json->deleted_song_id,
            "deleted_song_name" => $json->deleted_song_name
        ));

    }

//MOVED TO SONG CONTROLLER
    /**  adds the passed song to the playlist belonging to the specified session
     * @return mixed
     */
    public function AJAXAddSongs(){
        $inputData = Input::get('formData');
        $json =  json_decode($inputData);

        //find in database how many songs are there already with this session token
        $numOfSongs = Song::where('session_token', $json->session_token)->count();

        for($i=0 ; $i < $json->num_new_songs ; $i++){

            $newSong = new Song();
            $newSong->session_token = $json->session_token;
            $newSong->songid = $json->new_songs[$i]->id;
            $newSong->songname = $json->new_songs[$i]->name;
            $newSong->votes = 0;
            $newSong->priority = ($numOfSongs + $i);
            $newSong->save();

        }

        return Response::json(array(
            "success" => true,
            "session_token" => $json->session_token,
            "added_songs" => $json->num_new_songs
        ));
    }

//MOVED TO SESSION CONTROLLER
    /** the core synchronization method. This method returns a copy of the playlist belonging to the session so as to
     * update a user to changes
     * @return mixed
     */
    public function AJAXGetArrays(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $songData = Song::getPlaylist($json->session_token);
        $userData = User::where(array('session_token' => $json->session_token))->first();

        if(empty($songData)){
            return Response::json(array(
                "success" => false,
                "message" => "Song list returned no results",
                "error_code" => 404
            ));
        }else{

            return Response::json(array(
                'success' => true,
                'session_token' => $json->session_token,
                'song_list' => $songData,
                'song_list_length' => count($songData),
                'currently_playing' => $userData->currently_playing,
                'currently_playing_time' => $userData->host_time,
                'double_playlist_enabled' => $userData->double_playlist
            ));
        }
    }

//MOVED TO SESSION CONTROLLER
    /** unloads the DB data associated with the passed in session. This method is triggered everytime the host user
     * refreshes thier page as it is the reset point for the playlist
     * @return mixed
     */
    public function AJAXUnloadDBSession(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        User::where('session_token', '=', $json->session_token)->delete();
        Song::where('session_token', '=', $json->session_token)->delete();

        return Response::json(array(
            'data' => $json
        ));

    }

//MOVED TO ANALYTICS CONTROLLER
    public function AJAXDecrementGuest(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        User::where('session_token', '=', $json->session_token)->decrement('guests');

        return Response::json(array(
            'data' => $json
        ));
    }

//MOVED TO SESSION CONTROLLER
    public function AJAXUpdateTime(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $userData = User::where('session_token', '=', $json->session_token)->first();
        $userData->host_time = $json->currently_playing_time;
        $userData->save();

        return Response::json(array(
            'success' => true,
            'session_token' => $json->session_token,
            'host_time' => $json->currently_playing_time
        ));

    }

//MOVED TO SESSION CONTROLLER
    public function AJAXToggleDoublePlaylist(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $userData = User::where('session_token','=', $json->session_token)->first();
        $userData->double_playlist = $json->double_playlist;
        $userData->save();

        return Response::json(array(
            'success' => true,
            'session_token' => $json->session_token
        ));
    }
}
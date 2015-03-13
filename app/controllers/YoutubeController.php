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
        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);

        $search = $formFields['search'];

        $results = $this->searchForVideos($search);

        return Response::json(array(
            'success' => true,
            'data' => $results
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

    /** updates the current song for the specified session. This is to keep other clients up to date with the currently
     * running song and to enforce validation if they are behind in updating before making a change (validation is
     * not yet implemented)
     * @return mixed
     */
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

    /** submits a vote for a song, changing the order of the playlist based on the newly added vote. The ordering is
     * sorted on the client side and then passed to the server to implement and update all other clients
     * @return mixed
     */
    public function AJAXSubmitVote(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $json->song_id))->update(array('votes' => $json->vote_count));

        $newOrder = $json->new_song_order;

        //update the priority of the playlist
        $count = 0;
        foreach($newOrder as $songObj){
            DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $songObj->id))->update(array('priority'=> $count));
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

    /** deletes the passed song from the playlist belonging to the specified session
     * @return mixed
     */
    public function AJAXDeleteSong(){
        $inputData = Input::get("formData");
        $json = json_decode($inputData);

        DB::table('songlist')->where(array("session_token" => $json->session_token, "songid" => $json->deleted_song_id))->delete();

        //$user = DB::table('user')->select("currently_playing")->where("session_token", $json->session_token)->get();

        //$newOrder = $this->reprioritize($json->session_token, $user[0]->currently_playing);

        $newOrder = $json->new_song_order;

        $count = 0;
        foreach($newOrder as $songObj){
            DB::table('songlist')->where(array('session_token' => $json->session_token, 'songid' => $songObj->id))->update(array('priority'=> $count));
            $count++;
        }

        return Response::json(array(
            "success" => true,
            "session_token" => $json->session_token,
            "deleted_song_id" => $json->deleted_song_id,
            "deleted_song_name" => $json->deleted_song_name
        ));

    }

    /**  adds the passed song to the playlist belonging to the specified session
     * @return mixed
     */
    public function AJAXAddSongs(){
        $inputData = Input::get('formData');
        $json =  json_decode($inputData);

        //find in database how many songs are there already with this session token
        $numOfSongs = DB::table('songlist')->where('session_token', $json->session_token)->count();

        for($i=0 ; $i < $json->num_new_songs ; $i++){

            DB::table('songlist')->insert(
                array( "session_token" => $json->session_token, "songid" => $json->new_songs[$i]->id, "songname" => $json->new_songs[$i]->name, "votes" => 0, "priority" => ($numOfSongs +$i))
            );
        }

        return Response::json(array(
            "success" => true,
            "session_token" => $json->session_token,
            "added_songs" => $json->num_new_songs
        ));
    }

    /** the core synchronization method. This method returns a copy of the playlist belonging to the session so as to
     * update a user to changes
     * @return mixed
     */
    public function AJAXGetArrays(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        $songData = DB::table('songlist')->select('songname','songid', 'votes')->where(array('session_token' => $json->session_token))->orderBy(DB::raw('ABS(priority)'), 'asc')->get();
        $userData = DB::table('user')->select('currently_playing')->where(array('session_token' => $json->session_token))->get();

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
                'currently_playing' => $userData[0]->currently_playing
            ));
        }
    }

    /** unloads the DB data associated with the passed in session. This method is triggered everytime the host user
     * refreshes thier page as it is the reset point for the playlist
     * @return mixed
     */
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
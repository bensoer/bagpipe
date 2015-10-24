<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 23/10/15
 * Time: 10:58 PM
 */
class SessionController extends BaseController
{

    /** updates the current song for the specified session. This is to keep other clients up to date with the currently
     * running song and to enforce validation if they are behind in updating before making a change (validation is
     * not yet implemented)
     * @return mixed
     */
    public function UpdateCurrentSong(){
        $json = $this->parseJsonInput();

        User::where('session_token', $json->session_token)
            ->update(array('currently_playing' => $json->currently_playing));


        return Response::json(array(
            'success' => true,
            'data' => $json
        ));


    }


    /** the core synchronization method. This method returns a copy of the playlist belonging to the session so as to
     * update a user to changes
     * @return mixed
     */
    public function GetArrays(){
        $json = $this->parseJsonInput();

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

    /** unloads the DB data associated with the passed in session. This method is triggered everytime the host user
     * refreshes thier page as it is the reset point for the playlist
     * @return mixed
     */
    public function UnloadDBSession(){
        $inputData = Input::get('formData');
        $json = json_decode($inputData);

        User::where('session_token', '=', $json->session_token)->delete();
        Song::where('session_token', '=', $json->session_token)->delete();

        return Response::json(array(
            'data' => $json
        ));

    }


    public function ToggleDoublePlaylist(){
        $json = $this->parseJsonInput();

        $userData = User::where('session_token','=', $json->session_token)->first();
        $userData->double_playlist = $json->double_playlist;
        $userData->save();

        return Response::json(array(
            'success' => true,
            'session_token' => $json->session_token
        ));
    }


    public function UpdateTime(){
        $json = $this->parseJsonInput();

        $userData = User::where('session_token', '=', $json->session_token)->first();
        $userData->host_time = $json->currently_playing_time;
        $userData->save();

        return Response::json(array(
            'success' => true,
            'session_token' => $json->session_token,
            'host_time' => $json->currently_playing_time
        ));

    }


}
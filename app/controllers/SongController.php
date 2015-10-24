<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 23/10/15
 * Time: 10:52 PM
 */
class SongController extends BaseController
{


    /** deletes the passed song from the playlist belonging to the specified session
     * @return mixed
     */
    public function DeleteSong(){
        $json = $this->parseJsonInput();

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

    /**  adds the passed song to the playlist belonging to the specified session
     * @return mixed
     */
    public function AddSongs(){
        $json = $this->parseJsonInput();

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

    /** submits a vote for a song, changing the order of the playlist based on the newly added vote. The ordering is
     * sorted on the client side and then passed to the server to implement and update all other clients
     * @return mixed
     */
    public function VoteSong(){
        $json = $this->parseJsonInput();

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

}
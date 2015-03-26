<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2015
 * Time: 11:05 PM
 */

//use Illuminate\Auth\UserInterface;
//use Illuminate\Auth\Reminders\RemindableInterface;

class Song extends Eloquent{

    protected $table  = "songs";
    protected $guarded = array('id');
    public $timestamps = false;

    protected function users(){
        return $this->belongsTo('user');
    }

    public static function getPlaylist($sessionToken){
        return DB::table('songs')
            ->select('songname','songid', 'votes')
            ->where(array('session_token' => $sessionToken))
            ->orderBy(DB::raw('ABS(priority)'), 'asc')
            ->get();
    }





}
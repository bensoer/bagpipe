
//class attribute
Playlist.prototype.fullPlaylist = new Array();
Playlist.prototype.arrayUpdated = false; //becomes true after the first update. Used for guest page to avoid blank pages


//contrustor


/**
 * Playlist takes the rate at which it needs to update the server in milisecond, aswell as the session token needed
 * to update the appropriate content on the server
 * @param rate the rate in milliseconds the server is updated
 * @param sessionToken the session token the server uses to identify the user's playlist
 * @constructor
 */
function Playlist(rate, sessionToken){
    this.nowPlayingIndex = 0;
    this.sessionToken = sessionToken;

    //setInterval passes a "this" that referres to the window, not to our object, so we need ot make out own this
    //and pass it
    var _this = this;
    setInterval(function(){_this.updateArray()}, rate);

};

//class methods

/**
 * getNowPlaying fetches the song that should now be playing on the playlist as determined by the nowPlayingIndex
 * @returns returns the Song object in the playlist that is currently playing otherwise null if the playlist is empty
 * or reached the end
 */
Playlist.prototype.getNowPlaying = function(){

    //if all songs have been played, return null
    if(this.nowPlayingIndex >= this.fullPlaylist.length){
        return null
    }else{
        return this.fullPlaylist[this.nowPlayingIndex];
    }

}
/**
 * getUpNext returns an array of Song objects of the songs next to be played in the playlist, in the order that they
 * will be played
 * @returns an array of Song objects to be played next in the playlist in the order they will be played
 */
Playlist.prototype.getUpNext = function(){

    var upNextArray = new Array();
    for(var i = this.nowPlayingIndex + 1 ; i < this.fullPlaylist.length ; ++i){
        upNextArray.push(this.fullPlaylist[i]);
    }
    return upNextArray;

}

/**
 * getNextToBePlayed returns the Song object that will be played next, but DOES NOT actualy move forward the nowPlayingIndex
 * to that song
 * @returns a Song object of the next song to be played on the playlist
 */
Playlist.prototype.getNextToBePlayed = function(){

    //alert("now playing in getNext: " + this.nowPlayingIndex);

    if(this.nowPlayingIndex + 1 > this.fullPlaylist.length){
        return null;
    }else{
        return this.fullPlaylist[this.nowPlayingIndex +1];
    }

}

/**
 * getNextSong moves the playlist to the next song and returns the new song that is now playing. nowPlayingIndex is
 * incremented in this method
 * @returns a Song object of the song that is now playing
 */
Playlist.prototype.getNextSong = function(){
    var nextSong = this.getNextToBePlayed();

    if(nextSong != null){
        this.nowPlayingIndex++;

        var json = {
            "session_token" : this.sessionToken,
            "currently_playing": this.nowPlayingIndex
        }
        var url = "/updateCurrent";
        this.updateServer(url,json);
    }
    return nextSong;
}

/**
 * addToPlaylist adds the parameter passed array of Song objects to the playlist in the order they are presented in the
 * passed in array
 * @param newSongsArray the array of Song objects to be added to the playlist
 */
Playlist.prototype.addToPlaylist = function(newSongsArray){

    for(var i = 0; i < newSongsArray.length; ++i){
        this.fullPlaylist.push(newSongsArray[i]);
    }


    //update server
    var json = {
        "session_token" : this.sessionToken,
        "new_songs" : newSongsArray,
        "num_new_songs" : newSongsArray.length
    }
    var url = "/addToPlaylist";

    this.updateServer(url,json);

}

/**
 * getLength gets the length of the playlist at the time of the method being called
 * @returns the length of the playlist
 */
Playlist.prototype.getLength = function(){
    return this.fullPlaylist.length;
}

/**
 * isEmpty determins if the playlist is empty or not
 * @returns {boolean} true = the playlist is empty, false = the playlist is not empty
 */
Playlist.prototype.isEmpty = function(){
    if(this.fullPlaylist.length > 0){
        return false;
    }else{
        return true;
    }
}

/**
 * deleteSong deletes the parameter passed song with the matching song ID from the playlist and then re-sorts the
 * playlist to ensure it is in the correct order with votes
 * @param songID - the ID of the song to be deleted
 */
Playlist.prototype.deleteSong = function(songID){

    var song;

    for(var i = 0; i < this.fullPlaylist.length; ++i){
        if(this.fullPlaylist[i].getID() == songID){
            alert('deleting: ' + this.fullPlaylist[i].getName());
            song = this.fullPlaylist[i];
            this.fullPlaylist.splice(i,1); //deletes 1 item at position i
            break;
        }
    }
    this.sortPlaylist();

    var json = {
        "session_token" : this.sessionToken,
        "deleted_song_id" : song.getID(),
        "deleted_song_name" : song.getName(),
        "new_song_order" : this.fullPlaylist
    }
    var url = "/deleteSong";

    this.updateServer(url, json);


}

/**
 * sortPlaylist is meant to be a private method which resorts the playlist based on vote counts of the songs. If votes
 * are the same, the items are not switched. Only when an item lower on the list has higher votes is it moved up.
 * sortPlaylist also only sorts the songs that are up next and does not include any songs that have or are currently
 * playing. The sort uses a n^2 Bubble Sort
 */
Playlist.prototype.sortPlaylist = function(){

    for(var i = this.nowPlayingIndex + 1 ; i < this.fullPlaylist.length ; ++i){
        for(var j = i+1; j < this.fullPlaylist.length; ++j){
            if(this.fullPlaylist[i-1].getVoteCount() < this.fullPlaylist[i].getVoteCount()){
                //swap
                var temp = this.fullPlaylist[i-1];
                this.fullPlaylist[i-1] = this.fullPlaylist[i];
                this.fullPlaylist[i] = temp;
            }
        }
    }
}

/**
 * updateServer is an interface method that makes calls to the server using the parameter passed url and json data and
 * return the results sent back by the server
 * @param url - the url that will be called on the server
 * @param json - the data being sent to the server
 * @returns a json object with the data being returned by the server
 */
Playlist.prototype.updateServer = function(url, json){

    var data = JSON.stringify(json);
    //alert("SENDING: \n" + data);

    return $.post(url, {formData: data});
}

/**
 * updateArray is a private method that is called by the playlist object at set timed intervals to update the playlist.
 * This is used for when guests add new songs or songs are voted and therefor the playlist order has changed. The
 * currently playing song index is also updated from this method. The interval at which the playlist is updated can be
 * set in the constructor of the Playlist object.
 */
Playlist.prototype.updateArray = function(){

    var _this = this;

    //alert("updating array");

    var json = {
        "session_token": this.sessionToken,
        "currently_playing": this.nowPlayingIndex
    }
    var url = "/getArrays"

    var response = this.updateServer(url,json);

    response.done(function(entity){
        if(entity.success){
            //alert("RESPONSE: \n" + JSON.stringify(entity));

            _this.nowPlayingIndex = entity.currently_playing;
            _this.fullPlaylist = new Array();
            //alert("playlist rebuild: " + _this.fullPlaylist);
            for(var i = 0 ; i < entity.song_list_length; ++i){
                var song = new Song(entity.song_list[i].songname, entity.song_list[i].songid);
                song.setVoteCount(entity.song_list[i].votes);
                _this.fullPlaylist.push(song);
                //alert("playlist is now: " + _this.fullPlaylist);
            }
            _this.arrayUpdated = true;
            //alert("playlist after build: " + JSON.stringify(_this.fullPlaylist));

        }
    })
}

Playlist.prototype.incrementSongVote = function(songID){
    for(var i = this.nowPlayingIndex+1; i < this.fullPlaylist.length; ++i){
        if(this.fullPlaylist[i].getID() == songID){
            this.fullPlaylist[i].incrementVoteCount();
            alert("new vote value: " + this.fullPlaylist[i].getVoteCount());
            this.sortPlaylist();

            var json = {
                "session_token": this.sessionToken,
                "song_id": this.fullPlaylist[i].getID(),
                "song_name": this.fullPlaylist[i].getName(),
                "vote_count": this.fullPlaylist[i].getVoteCount(),
                "new_song_order":this.fullPlaylist
            }

            alert("Increment Song vote is sending: \n" + JSON.stringify(json));

            var url = "/submitVote";

            var response = this.updateServer(url,json);

            response.done(function(entity){
                alert("Increment Song vote recieved: \n" + JSON.stringify(entity));
            })



            return this.fullPlaylist[i];
        }
    }
    return null // the song could not be found

}

Playlist.prototype.decrementSongVote = function(songID){
    for(var i = this.nowPlayingIndex+1; i < this.fullPlaylist.length; ++i){
        if(this.fullPlaylist[i].getID() == songID){
            this.fullPlaylist[i].decrementVoteCount();

            this.sortPlaylist();

            var json = {
                "session_token": this.sessionToken,
                "song_id": this.fullPlaylist[i].getID(),
                "song_name": this.fullPlaylist[i].getName(),
                "vote_count": this.fullPlaylist[i].getVoteCount(),
                "new_song_order":this.fullPlaylist
            }

            var url = "/submitVote";

            this.updateServer(url,json);

            return this.fullPlaylist[i];
        }
    }
    return null // the song could not be found
}








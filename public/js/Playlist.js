
//class attribute
Playlist.prototype.fullPlaylist = new Array();


//contrustor

/* Playlist takes the rate at which it needs to update the server in milisecond, aswell as the session token needed
to update the appropriate content on the server */
function Playlist(rate, sessionToken){
    this.nowPlayingIndex = 0;
    this.sessionToken = sessionToken;

    //setInterval passes a "this" that referres to the window, not to our object, so we need ot make out own this
    //and pass it
    var _this = this;
    setInterval(function(){_this.updateArray()}, rate);

};

//class methods

Playlist.prototype.getNowPlaying = function(){

    //if all songs have been played, return null
    if(this.nowPlayingIndex >= this.fullPlaylist.length){
        return null
    }else{
        return this.fullPlaylist[this.nowPlayingIndex];
    }

}

Playlist.prototype.getUpNext = function(){

    var upNextArray = new Array();
    for(var i = this.nowPlayingIndex + 1 ; i < this.fullPlaylist.length ; ++i){
        upNextArray.push(this.fullPlaylist[i]);
    }
    return upNextArray;

}

/* Returns the next item in the playlist to be played. Returns null if there is nothing next */
Playlist.prototype.getNextToBePlayed = function(){

    //alert("now playing in getNext: " + this.nowPlayingIndex);

    if(this.nowPlayingIndex + 1 > this.fullPlaylist.length){
        return null;
    }else{
        return this.fullPlaylist[this.nowPlayingIndex +1];
    }

}

/* Returns the next item in the playlist to be played. Increments the nowPlayingIndex
    Returns null if there is nothing next */
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

Playlist.prototype.getLength = function(){
    return this.fullPlaylist.length;
}

Playlist.prototype.isEmpty = function(){
    if(this.fullPlaylist.length > 0){
        return false;
    }else{
        return true;
    }
}

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

Playlist.prototype.updateServer = function(url, json){

    var data = JSON.stringify(json);
    //alert("SENDING: \n" + data);

    return $.post(url, {formData: data});
}

Playlist.prototype.updateArray = function(){

    var json = {
        "session_token": this.sessionToken,
        "currently_playing": this.nowPlayingIndex
    }
    var url = "/getArrays"

    var response = this.updateServer(url,json);

    response.done(function(entity){
        if(entity.success){
            //alert("RESPONSE: \n" + JSON.stringify(entity));

            this.fullPlaylist = new Array();
            for(var i = 0 ; i < entity.song_list_length; ++i){
                this.fullPlaylist.push(entity.song_list[i]);
            }

        }
    })
}








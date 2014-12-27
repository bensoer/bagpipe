
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
    setInterval(function(){_this.updateServer()}, rate);

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

    alert("now playing in getNext: " + this.nowPlayingIndex);

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
    this.nowPlayingIndex++;
    return nextSong;
}

Playlist.prototype.addToPlaylist = function(song){
    this.fullPlaylist.push(song);
}

Playlist.prototype.updateServerPlaylist = function(){

    var json = {"session_token" : this.sessionToken, "playlist" : this.fullPlaylist};


    var data = JSON.stringify(json);
    var url = '/AddToPlaylist';

    alert(data);

    var post = $.post(url, {formData: data});
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

    for(var i = 0; i < this.fullPlaylist.length; ++i){
        if(this.fullPlaylist[i].getID() == songID){
            alert('deleting: ' + this.fullPlaylist[i].getName());
            this.fullPlaylist.splice(i,1); //deletes 1 item at position i
            return;
        }
    }

}

Playlist.prototype.updateServer = function(url, json){

    alert("now playing from updateServer: " + this.nowPlayingIndex);

    //var json = { "currently_playing" : this.nowPlayingIndex, "session_token" : this.sessionToken, "playlist" : this.fullPlaylist};


    var data = JSON.stringify(json);
    //var url = '/sync';

    alert(data);

    var post = $.post(url, {formData: data});
}


//DEBUG HACKS
Playlist.prototype.getPlaylist = function(){
    return this.fullPlaylist;
}







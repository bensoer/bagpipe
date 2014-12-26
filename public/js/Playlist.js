
//class attribute
Playlist.prototype.fullPlaylist = new Array();

//contrustor
function Playlist(){
    this.nowPlayingIndex = 0;
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
    alert("Retrieved data: \n " + song);
    this.fullPlaylist.push(song);
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


//DEBUG HACKS
Playlist.prototype.getPlaylist = function(){
    return this.fullPlaylist;
}







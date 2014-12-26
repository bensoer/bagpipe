
//constructor
function Song(songName, songID){
    this.name = songName;
    this.id = songID;
}

//class methods

Song.prototype.getName = function(){
    return this.name;
}

Song.prototype.getID = function(){
    return this.id;
}

Song.prototype.setName = function(songName){
    this.name = songName;
}

Song.prototype.setID = function(songID){
    this.id = songID;
}

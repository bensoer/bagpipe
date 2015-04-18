
//class attributes
Song.prototype.voteCount = 0;
Song.prototype.timeInSeconds = 0;

//constructor
/**
 * Song is an object containing all data required to access and use Youtube songs by the youtube player and the UI
 * presented to the user on the bagpipe website
 * @param songName - the name/title of the song - used for all text representations of the song
 * @param songID - the Youtube ID of the song - used by the player to play the appropriate song
 * @constructor
 */
function Song(songName, songID){
    this.name = songName;
    this.id = songID;
}

//class methods
/**
 * getName returns the name value of the Song object
 * @returns {*}
 */
Song.prototype.getName = function(){
    return this.name;
}
/**
 * getID returns the id value fo the Song object
 * @returns {*}
 */
Song.prototype.getID = function(){
    return this.id;
}
/**
 * setName sets the name value of the Song object
 * @param songName - the name/title of the song
 */
Song.prototype.setName = function(songName){
    this.name = songName;
}
/**
 * setID sets the id value of the Song object
 * @param songID - the Youtube ID of the song
 */
Song.prototype.setID = function(songID){
    this.id = songID;
}
/**
 * getVoteCount returns the number of votes the song has recieved
 * @returns the number of votes for this song
 */
Song.prototype.getVoteCount = function(){
    return this.voteCount;
}
/**
 * setVoteCount sets the number of votes for the song
 * @param newVoteCount - the number of votes to be set
 */
Song.prototype.setVoteCount = function(newVoteCount){
    this.voteCount = newVoteCount;
}
/**
 * incrementVoteCount increments the number of votes there are for the song
 */
Song.prototype.incrementVoteCount = function(){
    ++this.voteCount;
}
/**
 * decrementVoteCount decrements the number of votes there are for the song
 */
Song.prototype.decrementVoteCount = function(){
    --this.voteCount;
}

Song.prototype.setTime = function(timeInSeconds){
    this.timeInSeconds = timeInSeconds;
}
Song.prototype.getTime = function(){
    return this.timeInSeconds;
}

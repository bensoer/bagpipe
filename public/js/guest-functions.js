

var rate = 3 * 1000;
/*window.setInterval(getCurrentlyPlaying, rate);
window.setTimeout(getCurrentlyPlaying,0);
window.setTimeout(getUpNext,0);
window.setInterval(getUpNext, rate);*/

window.setInterval(updateDisplayLists, rate);
window.setTimeout(updateDisplayLists, 0);
var alreadyVoted = new Array();

var token = document.getElementById('session_token').innerHTML;
var playlist = new Playlist(5*1000, token );
playlist.updateArray();

var doublePlaylistEnabled = false;

/* Build Youtube Player */


// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
//tag.src = "http://www.youtube.com/apiplayer?enablejsapi=1&version=3";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);



// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '90',
        width: '120',
        playerVars: {
            controls:0,
            showinfo:0,
            modestbranding:1,
            iv_load_policy:3
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

/** Called by The Youtube Player is ready **/
function onPlayerReady(event) {

    var nowPlaying = playlist.getNowPlaying();

    if(nowPlaying == null){
        //document.getElementById('label').innerHTML = "You have not added anything to your playlist yet...";
    }else{
        updateDisplayLists();
        if(playlist.isDoublePlaylist()){
            //alert("is a double playlist");
  //          player.loadVideoById({videoId:nowPlaying.getID()});
            //alert("moving to time: " + nowPlaying.getTime());
  //          player.seekTo(nowPlaying.getTime(), false);
  //          player.playVideo();

            doublePlaylistEnabled = true;
        }else{
            player.pauseVideo();
        }

    }
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
/** called when the Youtube Player has had an event occur **/
function onPlayerStateChange(event) {

    if (event.data == YT.PlayerState.ENDED) {
        //document.getElementById("play").className = "glyphicon glyphicon-play";

        var nextSong = playlist.getNextSong();

        if (nextSong == null) {
            //once played all the videos should we stop or replay ??
        } else {
            updateDisplayLists();
            player.loadVideoById({videoId: nextSong.getID()});
            player.playVideo();
        }

    }
}

function playNextSong(){
    var nextSong = playlist.getNextSong();
    if(nextSong == null){

    }else{
        updateDisplayLists();
        player.loadVideoById({videoID: nextSong.getID()});
        player.playVideo();
    }
}

/* --                       --                      --              -- */


function getCurrentlyPlaying(){
    //var song = playlist.getNowPlaying();
    var song = playlist.getNowPlaying();

    var label = document.getElementById("now-playing-label");
    var youtubeLabel = document.getElementById("youtube-label");

    var playerDiv = document.getElementById("player");


    //this is the controller when double playlist is enabled / disabled part way through playing
    if(playlist.isDoublePlaylist()){
        //alert("Double Playlist Detected");

        if(!doublePlaylistEnabled){
            label.style.display = "none";
            addYoutubePlayer();
            doublePlaylistEnabled = true;
        }


        var totalSongLength = player.getDuration();
        var currentSongTime = player.getCurrentTime();

        //if there is less then or equal to 10 seconds different, wait it out
        if(totalSongLength - currentSongTime <= 10){

        }else{
            //update the song label
            youtubeLabel.innerHTML = playlist.getNowPlaying().getName();

        }


    }else{
        //alert("No Double Playlist Detected");
        label.style.display = "block";
        removeYoutubePlayer();
        doublePlaylistEnabled = false;


        //alert(playlist.doublePlaylist);
        //alert(song);
        if(song != null){
            var lbl = document.getElementById("label");
            lbl.innerHTML = "";

            var link = document.createElement("a");
            link.href= "https://www.youtube.com/watch?v=" + song.getID();
            link.target = "_blank";
            link.innerHTML = song.getName();

            lbl.appendChild(link);
        }
    }

    var loader = document.getElementById("loading-placeholder");
    loader.style.display = "none";


/*
    var token = document.getElementById("session_token");
    var json = {"session_token": token.innerHTML};

    var data = JSON.stringify(json);
    var url = "/getCurrent";

    var post = $.post(url, {formData: data});

    post.done(function(result){
    if(result.success == false){

    }else{
    var lbl = document.getElementById("label");
    lbl.innerHTML = "";

    var link = document.createElement("a");
    link.href= "https://www.youtube.com/watch?v=" + result.id;
    link.target = "_blank";
    link.innerHTML = result.name;

    lbl.appendChild(link);
    }

    });
*/
}

function removeYoutubePlayer(){
    player.pauseVideo();
    var playerModule = document.getElementById("player-module");
    playerModule.style.display = "none";

}

function addYoutubePlayer(){

    while(!playlist.arrayUpdated){
        //wait for the response
    }
    //make the player visible and then start the current song
    var playerModule = document.getElementById("player-module");
    playerModule.style.display = "block";

    var nowPlaying = playlist.getNowPlaying();

    alert("adding youtube player. moving to time: " + nowPlaying.getTime());

    player.loadVideoById({videoId:nowPlaying.getID()});
    player.pauseVideo();
    player.seekTo(nowPlaying.getTime(), false);
    player.playVideo();


}

function getUpNext(){
    /*var token = document.getElementById("session_token");
    var list = document.getElementById('list');
    var json = {"session_token": token.innerHTML};

    var data = JSON.stringify(json);
    var url = "/getUpNext";

    var post = $.post(url, {formData: data});

    //alert(token.innerHTML);

    post.done(function(result){
    if(result.success == false){
    location.reload();
    }else{
    var json = JSON.parse(result);
    //alert(json[0] + "\n" + json[1] + "\n" + json[2] + "\n" +  json.length);
    list.innerHTML = '';*/

    var upNextList;
    if(playlist.arrayUpdated){
        upNextList = playlist.getUpNext();
        var list = document.getElementById('list');
        list.innerHTML = '';
    }else{
        return;
    }

    for(var i = 0; i < upNextList.length ; i++){

        var listItem = document.createElement("li");
        listItem.className = "queue-item";

        var rowDiv = document.createElement("div");
        rowDiv.className = "row";

        var tntDiv = document.createElement("div");
        tntDiv.className = "col-lg-8";

        var mediaDiv = document.createElement("div");
        mediaDiv.className = "media";

        var thumbLink = document.createElement("a");
        thumbLink.className = "meda-left queue-thumb";
        thumbLink.target = "_blank";
        thumbLink.href="https://www.youtube.com/watch?v=" + upNextList[i].getID();

        // var thumbImg = document.createElement("img");
        // thumbImg.src = "http://img.youtube.com/vi/" + json[1][i] + "/hqdefault.jpg";
        // thumbImg.style.height = "50px";
        // thumbImg.style.width = "67px";
        // thumbImg.style.verticalAlign = "middle";

        //thumbLink.appendChild(thumbImg);

        var mediaBodyDiv = document.createElement("div");
        mediaBodyDiv.className = "media-body media-middle";
        mediaBodyDiv.target = "_blank";

        var mediaBodyLink = document.createElement("a");
        mediaBodyLink.href="https://www.youtube.com/watch?v=" + upNextList[i].getID();
        mediaBodyLink.target = "_blank";

        var mediaBodySpan = document.createElement("span");
        mediaBodySpan.className = "media-heading";
        var songName = document.createTextNode(upNextList[i].getName());
        mediaBodySpan.appendChild(songName);

        mediaBodyLink.appendChild(mediaBodySpan);
        mediaBodyDiv.appendChild(mediaBodyLink);
        mediaDiv.appendChild(thumbLink);
        mediaDiv.appendChild(mediaBodyDiv);
        tntDiv.appendChild(mediaDiv);

        //votes and upvoting buttons

        var votingDiv = document.createElement("div");
        votingDiv.id = "voting";
        votingDiv.className = "btn-group pull-right";
        votingDiv.role = "group";
        votingDiv.setAttribute("aria-label","...");

        var votingBtn = document.createElement("button");
        votingBtn.type = "button";
        votingBtn.className = "btn btn-default votes";
        votingBtn.disabled = true;

        //votingBtn.setAttribute("meta-id", json[1][i]);

        //why the fuck do onclicks not work...
        //votingBtn.onclick = submitVote;

        /*function(){
         var token = document.getElementById("session_token");
         var videoId = this.id;

         alert("token: " + token.innerHTML + "\n videoId: " + videoId);
         };*/

        var votingSpan = document.createElement("span");
        votingSpan.className = "votes-number";
        var voteString = upNextList[i].getVoteCount();
        var voteNum = document.createTextNode("" + voteString);
        votingSpan.appendChild(voteNum);
        votingBtn.appendChild(votingSpan);

        var upVoteBtn = document.createElement("button");
        upVoteBtn.type = "button";
        upVoteBtn.className = "btn btn-default upvote";

        //upVoteBtn.setAttribute("meta-id", json[1][i]);
        upVoteBtn.value = upNextList[i].getID();

        upVoteBtn.onclick = submitVote;

        var upVoteSpan = document.createElement("span");
        upVoteSpan.className = "upvote-icon glyphicon glyphicon-chevron-up";
        upVoteSpan.setAttribute("aria-hidden", "true");
        upVoteBtn.appendChild(upVoteSpan);

        votingDiv.appendChild(votingBtn);
        votingDiv.appendChild(upVoteBtn);

        rowDiv.appendChild(tntDiv);
        rowDiv.appendChild(votingDiv);

        listItem.appendChild(rowDiv);

        document.getElementById("list").appendChild(listItem);
    }
}

/**
 * Updates the upNext and the nowPlaying lists GUI's
 */
function updateDisplayLists(){
    if(!playlist.isEmpty()){
        //document.getElementById('label').innerHTML = playlist.getNowPlaying().getName();
        getCurrentlyPlaying();
        getUpNext();
    }

}

/** triggered when submitting a search for songs **/
$("#searchSong").submit(function(event){
    event.preventDefault();

    var $form = $( this ),
    data = $form.serialize(),
    url = "/searchSong";

    var posting = $.post( url, { formData: data } );

    posting.done(function(results){
    if(results.success){
    //window.alert("SUCCESSSSSS" + results.data);

    var list = document.getElementById('search_list');
    list.innerHTML = '';


    for (var i=0; i < results.data[1].length; i++){

    var link=document.createElement("a");
    var textnode=document.createTextNode(results.data[1][i]);
    link.className = "list-group-item";
    link.href="#";
    link.style.borderRadius = 0;
    link.id = results.data[3][i];

    /*var checkbox = document.createElement("input");
     checkbox.type="checkbox";
     //checkbox.innerHTML = results.data[1][i];
     checkbox.name = results.data[1][i];
     checkbox.value = results.data[3][i]; //store videoID in the checkbox value*/

    //checkbox.onClick future implementation allowing onclick adding of the song to the list

    /*link.appendChild(checkbox);*/
    link.appendChild(textnode);

    document.getElementById("search_list").appendChild(link);
    }

    var submitSelectedBtn = document.createElement("button");
    submitSelectedBtn.innerHTML = "Add To List";
    submitSelectedBtn.className = "btn btn-default";

    submitSelectedBtn.addEventListener("click", addToPlaylist);

    document.getElementById("search_list").appendChild(submitSelectedBtn);

    }else{
    window.alert("A Serious Error Has Occured. Please refresh the Host's page and try again");
    }
    });
    });

//don't bother updating this list, it will be updated on the next sync call
function addToPlaylist(){

    /*var results = document.getElementById("search_list").getElementsByTagName('A');
    var token = document.getElementById("session_token");

    var requested = new Array();

    for(var i = 0; i < results.length; i++){
    if(results[i].classList.contains("active")){
    requested.push(results[i].id);
    }
    }

    document.getElementById('search_list').innerHTML = ''; //clear the search list

    requested.push(token.innerHTML);
    var data = JSON.stringify(requested);
    var url = "/addToPlaylist";
    $.post(url, {formData: data });

    document.getElementById("search").value = "";
    }*/

    var results = document.getElementById("search_list").getElementsByTagName('A');
    var list = document.getElementById('search_list');
    var token = document.getElementById("session_token");
    var wasEmptyBefore = false;

    //if the playlist is empty then this is the first time adding to it
    if(playlist.isEmpty()){
        wasEmptyBefore = true;
    }

    var array = new Array();
    for(var i = 0; i <results.length; i++){
        if(results[i].classList.contains("active")){
            //for each item in the results, if checked, add to the playlist
            array.push(new Song(results[i].innerHTML, results[i].id));
        }
    }

    playlist.addToPlaylist(array);


    list.innerHTML = "";

    //means the video list was previously empty, so youtube has attempted to load and failed to load video
    //so now that we have a/some song(s), trigger it to play the next video which is now the first song
    if(wasEmptyBefore){
        onPlayerReady(); // semi-hack
    }else{
        updateDisplayLists();
    }

    document.getElementById("search").value = "";
}

function submitVote() {
    /* var token = document.getElementById("session_token");
     //var videoId = this.getAttribute("meta-id");
     var videoId = this.value;
     var button = videoId + "Button";
     var icon = videoId + "Icon";
     //alert("token: " + token.innerHTML + "\n" + "videoId : " + videoId);

     var isVoted = false;
     //alert("already voted length: " + alreadyVoted.length);
     for(var i = 0 ; i < alreadyVoted.length; i++){
     if(videoId === alreadyVoted[i]){
     alert("You have already voted for this song. You can not vote again");
     //TODO: Change upvote color on button click
     // document.getElementById(button).disabled = true;
     //document.getElementById(icon).style.color = "#449d44";
     //      document.getElementById("h5-FJsYj1ckButton").style.color = "#449d44";
     //this.disabled();
     //this.style.color = "#449d44";
     isVoted = true;
     }
     }

     if(!isVoted){
     //alert(voteStatus);
     //alert("token: " + token.innerHTML + "\n videoId: " + videoId);

     var json = {"session_token": token.innerHTML, "videoid": videoId};
     var data = JSON.stringify(json);
     var url = "./submitVote";
     var post = $.post(url, {formData: data});
     alreadyVoted.push(videoId);
     //TODO: Change upvote color on button click
     //document.getElementById(button).disabled = true;
     //document.getElementById(icon).style.color = "#449d44";
     //this.disabled = true;
     // this.style.color = "#449d44";
     getUpNext();
     }

     }*/
    window.alert("got value : " + this.value);
    var result = playlist.incrementSongVote(this.value);

    if(result == null){
        window.alert("Your vote failed to complete. Please check your network connection and try again");
    }else{
        updateDisplayLists();
    }

}

/**
 * When the guest leaves, tell the server there is one less guest now on the playlist
 */
$(window).bind('unload', function(){
    var token = document.getElementById("session_token");
    var json = {session_token: token.innerHTML};
    var data = JSON.stringify(json);
    var url3 = "/api/playlist/delete/guest";
    var post = $.post(url3, {formData: data});

});



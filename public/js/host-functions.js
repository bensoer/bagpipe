
<!-- AJAX/JAVASCRIPT/YOUTUBE API -->

// create a playlist on page load
var token = document.getElementById('session_token').innerHTML;
var playlist = new Playlist(5*1000, token); // create playlist
window.setInterval(updateDisplayLists,5*1000); // update display with playlist data timer


// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
//tag.src = "http://www.youtube.com/apiplayer?enablejsapi=1&version=3";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var rate = 10*1000;
//window.setInterval(resyncArrays, rate);
buildUpNextList();



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
        document.getElementById('label').innerHTML = "You have not added anything to your playlist yet...";
    }else{
        updateDisplayLists();
        player.loadVideoById({videoId:nowPlaying.getID()});
        player.playVideo();
    }
}


// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
/** called when the Youtube Player has had an event occur **/
function onPlayerStateChange(event) {

    if (event.data == YT.PlayerState.ENDED) {
        document.getElementById("play").className = "glyphicon glyphicon-play";

        var nextSong = playlist.getNextSong();

        if (nextSong == null) {
            //once played all the videos should we stop or replay ??
        } else {
            updateDisplayLists();
            player.loadVideoById({videoId: nextSong.getID()});
            player.playVideo();
        }

    } else if (event.data == YT.PlayerState.PLAYING) {
        document.getElementById("play").className = "glyphicon glyphicon-pause";
        var playerTotalTime = player.getDuration();
        my_timer = setInterval(function () {

            var playerCurrentTime = player.getCurrentTime();
            var minutes = Math.floor(((playerTotalTime / 60) - (playerCurrentTime / 60)) % 60);
            var seconds = Math.floor(playerTotalTime - playerCurrentTime) % 60;
            var hours = Math.floor((playerTotalTime / 3600) - (playerCurrentTime / 3600));
            if (seconds < 0) {
                minutes = 0;
                seconds = 0;
            }

            var timeyWimey = "";
            if (hours > 0) {
                timeyWimey += "" + hours + ":" + (minutes < 10 ? "0" : "");
                document.getElementById("progressBarID").style.width = "89%";
                document.getElementById("elapsedTimeID").style.width = "7%";
            } else {
                document.getElementById("progressBarID").style.width = "91%";
                document.getElementById("elapsedTimeID").style.width = "5%";
            }
            timeyWimey += "" + minutes + ":" + (seconds < 10 ? "0" : "");
            timeyWimey += "" + seconds;

            var playerTimeDifference = (playerCurrentTime / playerTotalTime) * 100;
            document.getElementById("prog-bar").style.width = Math.floor(playerTimeDifference) + "%";
            document.getElementById("yt-timer").innerHTML = timeyWimey;
        }, 1100);
    } else if (event.data == YT.PlayerState.PAUSED) {
        document.getElementById("play").className = "glyphicon glyphicon-play";
    } else {
        clearTimeout(my_timer);
    }

}
/**
 * Updates the upNext and the nowPlaying lists GUI's
 */
function updateDisplayLists(){
    document.getElementById('label').innerHTML = playlist.getNowPlaying().getName();
    buildUpNextList();
}

/**
* Starts the video Player
*/
function stopVideo(){
    player.pauseVideo();
}

/**
* Stops the video Player
*/
function playVideo(){
    player.playVideo();
}

/** changes the state of the video from play to pause and back **/
function changeState(){
    if(player.getPlayerState() == 1){
        document.getElementById("play").className = "glyphicon glyphicon-play";
        player.pauseVideo();
    }else{
        document.getElementById("play").className = "glyphicon glyphicon-pause";
        player.playVideo();
    }
}


function goToNext() {
    var song = playlist.getNextSong();

    if (song == null) {
        //no song to play
    } else {
        //alert(song.getName());
        updateDisplayLists();
        player.loadVideoById({videoId: song.getID()});
        player.playVideo();
    }
}

/**
* Loads the upNext list
*/
function buildUpNextList(){

    //var list = document.body.appendChild(document.getElementById("list"));
    var list = document.getElementById('list');
    list.innerHTML = '';

    var upNextList = playlist.getUpNext();


    //alert("TO Be Played: \n" + upNextList);

    for(var i = 0; i < upNextList.length; ++i){
        var node=document.createElement("li");
        node.className = "queue-item";
        var textnode=document.createTextNode(upNextList[i].getName());
        node.appendChild(textnode);

        //need to create the layout dynamicaly and while creating it, assign the videoID to the
        // "name" attribute of the button, that away can be retrieved when it is clicked
        //the video id will be in the videoIDs array in the same i position as this cycle in the loop

        var delBtn = document.createElement("a");
        delBtn.href = "#";
        delBtn.onclick = deleteSong;
        delBtn.name = upNextList[i].getID();
        delBtn.className = "deleteButton";
        delBtn.value = "Delete";
        delBtn.innerHTML = "Delete";

        list.appendChild(node);
        node.appendChild(delBtn);
    }
}

/** triggered when submitting a search for songs **/
$("#searchSong").submit(function(event){
    //function submitSearch(){
    //alert("Called");
    event.preventDefault();

    var submitBtn = document.getElementById("submit_search");

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

                //checkbox.appendChild(textnode);
                /*link.appendChild(checkbox);*/
                link.appendChild(textnode);

                document.getElementById("search_list").appendChild(link);
            }

            var submitSelectedBtn = document.createElement("button");
            submitSelectedBtn.innerHTML = "Add To List";
            submitSelectedBtn.className = "btn btn-default";
            submitSelectedBtn.borderRadius = 0;


            submitSelectedBtn.addEventListener("click", addToPlaylist);

            document.getElementById("search_list").appendChild(submitSelectedBtn);

        }else{
            window.alert("A Serious Error Has Occurred. Please refresh the Host's page and try again");
        }
    });
});

/**
* Gets all elements from the search list, looks for all the checkbox elements that are checked, and adds
* them to the lists
*/
function addToPlaylist(){
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

function deleteSong(){
    var songid = this.name;

    playlist.deleteSong(songid);
    updateDisplayLists();
}

/** triggers when the window is about to be unloaded. removes all database and playlist session information **/
$(window).bind('beforeunload', function() {
    return "All content in your playlist will be lost and guests will be revoked access to the playlist"

    });

$(window).bind('unload', function(){
    var token = document.getElementById("session_token");
    var json = {session_token: token.innerHTML};
    var data = JSON.stringify(json);
    var url3 = "/unloadDBSession";
    var post = $.post(url3, {formData: data});

    post.done(function(result){
    alert("Decoupling Sent \n" + result.data);

    });
    });



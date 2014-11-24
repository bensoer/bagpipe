@extends('layouts.party')

@section('meta-title', 'Host')
@stop

@section('navies')

<li class="page-scroll">
    <span id="sharelabel">Share</span>
</li>
<li class="page-scroll">
    <div id="sharecode">
        <!-- TODO: Sharecode goes here -->
        <span id="token-box">{{ $data['shareCode'] }}</span>
    </div>
</li>

@stop

@section('search')

<div id="searchbox">
    <form class="navbar-form navbar-left" role="search" method="POST" id="searchSong">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search" name="search" id="search">
              <p id="session_token" hidden>{{ $data['shareCode'] }}</p>
            </div>
    </form>
</div>

<!-- Search Results -->
<div class = "row">
    <div class="form-group">

        <!-- JavaScript loaded search list. Note: changes to list style need to be applied in JavaScript -->
        <div id="search_list" class="list-group" style="text-align:left">


        </div>
    </div>
</div>

@stop

@section('control')

<!-- Currently playing -->
<div id="current-song">
    <div class="row">
        <div class="form-group prev_next">
            <!-- JavaScript loaded now playing list -->
            <div class="col-lg-12">
                <div class="media">
                    <a class="media-left queue-thumb"
                       href="https://www.youtube.com/watch?v=fjC7dctw7LU<?php //echo $songlist[$i]->songid; ?>" target="_blank">
                        <!-- Youtube player module -->
                        <div id="player"></div>
                    </a>
                    <div class="media-body media-middle">
                        <span id="label" class="media-heading"><?php //echo $songlist[$i]->songname; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls: Play/Pause, Progress bar, Skip -->
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">
                 <div class="form-group prev_next">

                    <!-- Play/Pause button -->
                     <div class="col-md-1">
                         <button type="button" class="btn btn-default" aria-hidden="true" onclick="changeState()">
                            <span id="play" class="glyphicon glyphicon-pause"></span>
                         </button>
                     </div>

                    <!-- Progress bar -->
                    <div id="progressBarID" class="col-md-8">
                        <div class="progress">
                          <div id="prog-bar" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                    </div>

                    <!-- Elapsed time -->
                     <div id="elapsedTimeID" class="col-md-2">
                        <span id="yt-timer">0:00</span>
                     </div>

                    <!-- Skip button -->
                     <div class="col-md-1">
                         <button type="button" class="btn btn-default" onclick="goToNext()" aria-hidden="true">
                            <span class="glyphicon glyphicon-forward"></span>
                         </button>
                     </div>

                 </div>
             </div>
         </div>
    </div>
</div>

<!-- Queue of YT videos -->
<div id="queue-list">
    <div class="row">
        <div class="col-lg-12">
             <div class="form-group">
                    <!-- JavaScript and PHP loaded up next list-->
                  <div id="next">
                        <ul id="list"  style="list-style-type:none">
                            <?php //$songlist = $data['songlist'];
                                //for ($i = 1; $i < count($songlist); $i++){ ?>
                                <li class="queue-item">
                                    <div class="row">
                                        <!-- YT Thumbnail and title -->
                                        <div class="col-lg-8">
                                            <div class="media">
                                                <a class="media-left queue-thumb"
                                                   href="https://www.youtube.com/watch?v=<?php //echo $songlist[$i]->songid; ?>" target="_blank">
                                                    <img src="http://img.youtube.com/vi/<?php //echo $songlist[$i]->songid; ?>hqdefault.jpg">
                                                </a>
                                                <div class="media-body media-middle">
                                                    <a href="https://www.youtube.com/watch?v=<?php //echo $songlist[$i]->songid; ?>" target="_blank">
                                                        <span class="media-heading"><?php //echo $songlist[$i]->songname; ?></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Votes and upvote button -->
                                        <div id="voting" class="btn-group pull-right" role="group" aria-label="...">
                                          <button type="button" class="btn btn-default votes" disabled>
                                            <span class="votes-number"><?php //echo $songlist[$i]->votes ; ?></span>
                                          </button>
                                          <button type="button" class="btn btn-default upvote">
                                            <span class="upvote-icon glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                                          </button>
                                        </div>
                                    </div>
                                </li>
                           <?php //} ?>
                        </ul>
                  </div>

                        <!-- JavaScript loaded up next list-->
                      <div id="next"><ul id="list" style="list-style-type:none"></ul></div>

             </div>
        </div>
    </div>
</div>




<div class="container" style="min-height: 350px;">

    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">
                <div class="form-group">
                    <div class="col-xs-4 col-lg-offset-4">
                        <!-- JavaScript loaded search title -->
                        <h1 id="search_results_title"></h1>
                        <!-- JavaScript loaded search list. Note: changes to list style need to be applied in JavaScript -->
                        <div id="search_list" class="list-group" style="text-align:left"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->


<!-- AJAX/JAVASCRIPT/YOUTUBE API -->
        <script>


            <?php //$videoIDs = $data['videoIDs'];
            //$videoNames = $data['videoNames']; ?>
            /** videoIDs - main array referred to for loading and rendering playlist videos **/
            var videoIDs = Array(<?php //for($i=0;$i< count($videoIDs);++$i){ if($i == count($videoIDs)-1){ echo '"'.$videoIDs[$i].'"';}else{ echo '"'.$videoIDs[$i].'"'.",";}}?>);
            /** videoNames - main array referred to for namings of songs as they play **/
            var videoNames = Array(<?php //for($i=0;$i< count($videoNames);++$i){ if($i == count($videoNames)-1){ echo '"'.$videoNames[$i].'"';}else{ echo '"'.$videoNames[$i].'"'.",";}}?>);
            /** soFarPlayed - master counter for how far through the playlist the user is. Increments 1 ahead of currently playing video **/
            var soFarPlayed = 0;


          // 2. This code loads the IFrame Player API code asynchronously.
          var tag = document.createElement('script');

          tag.src = "https://www.youtube.com/iframe_api";
          //tag.src = "http://www.youtube.com/apiplayer?enablejsapi=1&version=3";
          var firstScriptTag = document.getElementsByTagName('script')[0];
          firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

           var rate = 10*1000;
           window.setInterval(resyncArrays, rate);
          getSongs();

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
                    modestbranding:0,
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
            //alert("player ready");
            var videoId = loadNextVideo();
            //alert("got video id");
            if(videoId == null){
                //alert("found its null");
                if(videoIDs.length == 0){
                    document.getElementById('label').innerHTML = "You have not added anything to your playlist yet...";
                    document.getElementById('current-song').getElementsByClassName("row")[1].style.borderBottom = "0";
                }
                //once its played all the videos should we stop or replay ??
            }else{
                //alert("found a video");
                player.loadVideoById({videoId:videoId});
                player.playVideo();
            }

          }

          // 5. The API calls this function when the player's state changes.
          //    The function indicates that when playing a video (state=1),
          //    the player should play for six seconds and then stop.
            /** called when the Youtube Player has had an event occur **/
          function onPlayerStateChange(event) {
            //if (event.data == YT.PlayerState.PLAYING && !done) {
              //setTimeout(stopVideo, 6000);
              //done = true;
            //}

            if ( event.data == YT.PlayerState.PLAYING ) {
                var playerTotalTime = player.getDuration();
                my_timer = setInterval( function ()
                {
                    var playerCurrentTime = player.getCurrentTime();
                    var minutes = Math.floor((playerTotalTime / 60) -(playerCurrentTime / 60));
                    var seconds = Math.floor(playerTotalTime - playerCurrentTime) % 60;
                    var hours = Math.floor((playerTotalTime / 3600) -(playerCurrentTime / 3600));
                    if (seconds < 0) {
                        minutes = 0;
                        seconds = 0;
                    }

                    var timeyWimey = "";
                    if (hours > 0)
                    {
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
                }, 1100 );
            } else {
                clearTimeout( my_timer );
            }

            // 0 means the video has ended
            if(event.data == 0){
                var videoId = loadNextVideo();
                if(videoId == null){
                    //once played all the videos should we stop or replay ??
                }else{
                    player.loadVideoById({videoId:videoId});
                    player.playVideo();
                    updateServerCurrentlyPlaying(soFarPlayed-1);
                }
            }
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

          //returns the id of the next video
          function loadNextVideo(){
            document.getElementById("yt-timer").innerHTML = "0:00";
            var nowPlayingLbl = document.getElementById('label');
            if(soFarPlayed >= videoIDs.length){
                return null;
            }else{
                    nowPlayingLbl.innerHTML = videoNames[soFarPlayed];
                    getSongs();
                    return videoIDs[soFarPlayed++];
                }
          }

          function goToPrevious(){
                if(soFarPlayed - 2 >= 0){
                    soFarPlayed = soFarPlayed - 2;

                     var videoId = loadNextVideo();
                       if(videoId == null){
                           //once played all the videos should we stop or replay ??
                       }else{
                           player.loadVideoById({videoId:videoId});
                           player.playVideo();
                           updateServerCurrentlyPlaying(soFarPlayed-1);
                       }
                }
          }

          function goToNext(){
               var videoId = loadNextVideo();
               if(videoId == null){
                    //alert("Got caught in here");
                   //once played all the videos should we stop or replay ??
               }else{
                   player.loadVideoById({videoId:videoId});
                   player.playVideo();
                   updateServerCurrentlyPlaying(soFarPlayed-1);
               }
            }

            /**
             * Loads songs from the videoNames array and builds the Up Next list from it
             */
          function getSongs(){

            //var list = document.body.appendChild(document.getElementById("list"));
            var list = document.getElementById('list');
            list.innerHTML = '';



            for (var i=soFarPlayed+1; i < videoNames.length; i++){

                var node=document.createElement("li");
                node.className = "queue-item";
                var textnode=document.createTextNode(videoNames[i]);
                node.appendChild(textnode);
                document.getElementById("list").appendChild(node);


               // li.innerHTML=li.innerHTML + videoNames[i];
                 //list.appendChild(li);

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


                        for (var i=soFarPlayed+1; i < results.data[1].length; i++){

                            var link=document.createElement("a");
                            var textnode=document.createTextNode(results.data[1][i]);
                            link.className = "list-group-item";
                            link.href="#";

                            var checkbox = document.createElement("input");
                            checkbox.type="checkbox";
                            //checkbox.innerHTML = results.data[1][i];
                            checkbox.name = results.data[1][i];
                            checkbox.value = results.data[3][i]; //store videoID in the checkbox value

                            //checkbox.onClick future implementation allowing onclick adding of the song to the list

                            //checkbox.appendChild(textnode);
                            link.appendChild(checkbox);
                            link.appendChild(textnode);

                            document.getElementById("search_list").appendChild(link);
                         }

                         var submitSelectedBtn = document.createElement("button");
                         submitSelectedBtn.innerHTML = "Add To List";
                         submitSelectedBtn.className = "btn btn-default";

                         submitSelectedBtn.addEventListener("click", addToPlaylist);

                         document.getElementById("search_list").appendChild(submitSelectedBtn);

                    }else{
                        window.alert("A Serious Error Has Occurred. Please refresh the Host's page and try again");
                    }
                });
             });
             //}
            /**
             * Gets all elements from the search list, looks for all the checkbox elements that are checked, and adds
             * them to the lists
             */
             function addToPlaylist(){
                var newSongs = new Array();
                var results = document.getElementById("search_list").getElementsByTagName('INPUT');
                var list = document.getElementById('search_list');
                var searchResultsTitle = document.getElementById('search_results_title');
                var wasEmptyBefore = false;

                //determine whether this is the first time the playlist is being added to
                if(videoIDs.length == 0){
                    //alert("was empty..");
                    wasEmptyBefore = true;
                }

                var addedCount = 0;
                for(var i = 0; i <results.length; i++){
                    if(results[i].type == "checkbox" && results[i].checked == true){
                        //value holds the videoID, name holds the video title
                        videoNames.push(results[i].name);
                        videoIDs.push(results[i].value);
                        newSongs.push(results[i].value);

                       //this is really bad..need to find more elegant
                        soFarPlayed--; //move back a step cuz soFarPlayed is 1 to far and getSongs needs to be 1 back to load bottom
                        getSongs();
                        soFarPlayed++; //move forward to realign where soFarPlayed supposed ot be for next song
                        //---------------------
                        addedCount++;

                    }
                }
                //

                list.innerHTML = "";
                if(addedCount > 1){
                    searchResultsTitle.innerHTML = "Songs Successfuly Added";
                }else{
                    searchResultsTitle.innerHTML = "Song Successfuly Added";
                }

                //means the video list was previously empty, so youtube has attempted to load and failed to load video
                //so now that we have a/some song(s), trigger it to play the next video which is now the first song
                if(wasEmptyBefore){
                    goToNext();
                }

                //AJAX back new results to the Server
                var token = document.getElementById("session_token");
                newSongs.push(token.innerHTML); //add token just to the end, hopefully does not carry over to videoID :S

               /* for(var i = 0; i < newSongs.length; i++){
                    alert(newSongs[i]);
                }*/

                var data = JSON.stringify(newSongs);
                var url2 =  "/addToPlaylist";
                var post = $.post( url2, { formData: data } );

               /* post.done(function(result){
                    if(result.success){
                        alert("Backend Updated");
                        alert(result.data);

                    }
                });*/




             }
             function updateServerCurrentlyPlaying(number){
                var token = document.getElementById("session_token");
                var json = {"currently_playing" :number, "session_token": token.innerHTML};

                var data = JSON.stringify(json);
                var url3 = "./updateCurrent";

                var post = $.post(url3, {formData: data});

               /* post.done(function(result){
                    if(result.success){
                        alert("Backend Updated");
                        alert(result.data);

                    }
                });*/
             }

             function resyncArrays(){
                var token = document.getElementById("session_token");
                var json = {"session_token": token.innerHTML};
                var data = JSON.stringify(json);
                var url = "/getArrays";
                var post = $.post(url, {formData: data});

                post.done(function(result){
                    if(result.success == false){
                        //alert("no songs");
                    }else{
                        var json  = JSON.parse(result);

                        while(videoIDs.length > 0){
                            videoIDs.pop();
                        }
                        while(videoNames.length > 0){
                            videoNames.pop();
                        }


                        for(var j = 0; j < json.length ; j++){
                            videoIDs.push(json[j].songid);
                            videoNames.push(json[j].songname);
                            //alert("pushing: " + videoNames[j] + "\n ArrayName len: " + videoNames.length + "\n ArrayIDs len: " + videoIDs.length);
                        }

                        soFarPlayed--; //HACK
                        getSongs(); //update the up next list with new updates
                        soFarPlayed++; //HACK

                    }



                });

             }

            /** triggers when the window is about to be unloaded. removes all database and playlist session information **/
             $(window).bind('beforeunload', function() {
                var token = document.getElementById("session_token");
                var json = {session_token: token.innerHTML};
                var data = JSON.stringify(json);
                var url3 = "/unloadDBSession";
                var post = $.post(url3, {formData: data});

                post.done(function(result){
                    //alert("Decoupling Sent \n" + result.data);

                });

              });

        </script>

</div>
<!-- /.container -->

@stop
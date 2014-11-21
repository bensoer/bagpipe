@extends('layouts.main')

@section('meta-title', 'Host')
@stop

@section('content')

<div class="container" style="min-height: 350px;">

    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>I am a host!</h1>
            Invite your crowd: <code>{{ $data['shareCode'] }}</code></p>

            <div class="col-lg-12 center-block">
                <form class="form-signin" role="form"  method="POST" id="searchSong">

                    <div class="form-group">
                        <div class="col-xs-4 col-lg-offset-4">

                                 <h2 class="form-signin-heading">Add To The List</h2>
                                 <div>
                                    <input type="text" class="form-control" name="search" id="search" required autofocus>

                                    <button id="submit_search" class="btn btn-lg btn-primary btn-block" type="submit" >Search</button>
                                </div>


                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-4 col-lg-offset-4">
                            <!-- JavaScript loaded search title -->
                            <h1 id="search_results_title"></h1></h1>
                            <!-- JavaScript loaded search list -->
                            <div id="search_list[]" class="list-group" style="text-align:left">


                            </div>
                        </div>

                </form>


            </div>


        </div>
    </div>
    <!-- /.row -->


    <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="col-lg-12 center-block">
                         <div class="form-group prev_next">

                                <h1>Now Playing</h1>
                                <!-- JavaScript loaded now playing list -->
                                <p id="label"></p></p>
                                <!-- Youtube player module -->
                                <div id="player"></div>

                         </div>
                     </div>
                 </div>
            </div>

    <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="col-lg-12 center-block">
                         <div class="form-group" style="border: 2px solid black;text-align:center">
                              <div >
                                    <button type="button" class="btn btn-default" style="float:left" onclick="goToPrevious()">Play Previous</button>
                                    <button type="button" class="btn btn-default" style="float:left" onclick="stopVideo()">Stop Playlist</button>


                                    <h1 style="display:inline">Up Next</h1>

                                    <button type="button" class="btn btn-default" style="float:right" onclick="goToNext()">Play Next</button>
                                    <button type="button" class="btn btn-default" style="float:right" onclick="playVideo()">Play/Resume Playlist</button>
                              </div>

                                <!-- JavaScript loaded up next list-->
                              <div id="next"><ul id="list" class="list-group" style="list-style-type:none"></ul></div>






                         </div>
                    </div>
                </div>
    </div>


            <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="col-lg-12 center-block">
                             <div class="form-group">



                             </div>


                        </div>
                    </div>
                </div>
        </div>

<!-- AJAX/JAVASCRIPT/YOUTUBE API -->
        <script>


            <?php $videoIDs = $data['videoIDs'];
            $videoNames = $data['videoNames']; ?>
            var videoIDs = Array(<?php for($i=0;$i< count($videoIDs);++$i){ if($i == count($videoIDs)-1){ echo '"'.$videoIDs[$i].'"';}else{ echo '"'.$videoIDs[$i].'"'.",";}}?>);
            var videoNames = Array(<?php for($i=0;$i< count($videoNames);++$i){ if($i == count($videoNames)-1){ echo '"'.$videoNames[$i].'"';}else{ echo '"'.$videoNames[$i].'"'.",";}}?>);
           var soFarPlayed = 0;
          // 2. This code loads the IFrame Player API code asynchronously.
          var tag = document.createElement('script');

          tag.src = "https://www.youtube.com/iframe_api";
          //tag.src = "http://www.youtube.com/apiplayer?enablejsapi=1&version=3";
          var firstScriptTag = document.getElementsByTagName('script')[0];
          firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

          getSongs();

          // 3. This function creates an <iframe> (and YouTube player)
          //    after the API code downloads.
          var player;
          function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
              height: '390',
              width: '640',
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

          // 4. The API will call this function when the video player is ready.
          function onPlayerReady(event) {
            var videoId = loadNextVideo();
            if(videoId == null){
                //once its played all the videos should we stop or replay ??
            }else{
                player.loadVideoById({videoId:videoId});
                player.playVideo();
            }

          }

          // 5. The API calls this function when the player's state changes.
          //    The function indicates that when playing a video (state=1),
          //    the player should play for six seconds and then stop.
          var done = false;
          function onPlayerStateChange(event) {
            //if (event.data == YT.PlayerState.PLAYING && !done) {
              //setTimeout(stopVideo, 6000);
              //done = true;
            //}
            if(event.data == 0){
                var videoId = loadNextVideo();
                if(videoId == null){
                    //once played all the videos should we stop or replay ??
                }else{
                    player.loadVideoById({videoId:videoId});
                    player.playVideo();
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

          //returns the id of the next video
          function loadNextVideo(){
            if(soFarPlayed >= videoIDs.length){
                return null;
            }else{
                document.getElementById("label").innerHTML = videoNames[soFarPlayed];
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
                       }
                }
          }
          function goToNext(){
               var videoId = loadNextVideo();
               if(videoId == null){
                   //once played all the videos should we stop or replay ??
               }else{
                   player.loadVideoById({videoId:videoId});
                   player.playVideo();
               }
            }

          function getSongs(){

            //var list = document.body.appendChild(document.getElementById("list"));
            var list = document.getElementById('list');
            list.innerHTML = '';


            for (var i=soFarPlayed+1; i < videoNames.length; i++){

                var node=document.createElement("li");
                var textnode=document.createTextNode(videoNames[i]);
                node.appendChild(textnode);
                document.getElementById("list").appendChild(node);


               // li.innerHTML=li.innerHTML + videoNames[i];
                 //list.appendChild(li);

            }
          }

            $("#searchSong").submit(function(event){
                //alert("Called");
                event.preventDefault();

                var submitBtn = document.getElementById("submit_search");
                var searchResultsTitle = document.getElementById("search_results_title");

                submitBtn.disabled = true;
                submitBtn.innerHTML = "Searching...";



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
                            checkbox.value = results.data[0][i]; //store videoID in the checkbox value
                            checkbox.id = "results[]";
                            //checkbox.onClick future implementation allowing onclick adding of the song to the list


                            link.appendChild(checkbox);
                            link.appendChild(textnode);

                            document.getElementById("search_list").appendChild(link);
                         }

                         var submitSelectedBtn = document.createElement("button");
                         submitSelectedBtn.innerHTML = "Add To List";
                         submitSelectedBtn.onClick = "addToPlaylist()"

                         document.getElementById("search_list").appendChild(submitSelectedBtn);





                    }else{
                        window.alert("FAAIAILLUURREEEE");
                    }

                    submitBtn.disabled = false;
                    submitBtn.innerHTML = "Search";
                    searchResultsTitle.innerHTML = "Results";
                });
             });

             function addToPlaylist(){
                console.error("Here");
                var results = document.getElementById("results[]");
                for(var i = 0; i <results.length; i++){
                    alert(results[i]);
                }
             }




        </script>

</div>
<!-- /.container -->

@stop
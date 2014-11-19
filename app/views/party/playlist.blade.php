@extends('layouts.main')

@section('meta-title', 'Login')
@stop

@section('content')

 <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div class="container" style="min-height: 350px;">

        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="col-lg-12 center-block">
                     <div class="form-group">

                            <h1>Now Playing</h1>
                            <div id="player"></div>

                     </div>
                 </div>
             </div>
        </div>

        <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="col-lg-12 center-block">
                             <div class="form-group">

                                    <h1>Up Next</h1>
                                    <div id="next" onLoad="getSongs()"></div>


                             </div>
                         </div>
                     </div>
                </div>
    </div>

    <script>

        var videoIDs = Array(<?php for($i=0;$i< count($videoIDs);++$i){ if($i == count($videoIDs)-1){ echo '"'.$videoIDs[$i].'"';}else{ echo '"'.$videoIDs[$i].'"'.",";}}?>);
       var soFarPlayed = 0;
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '390',
          width: '640',
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
      /*function stopVideo() {
        player.stopVideo();
        player.cueVideoById({videoId:"rie-hPVJ7Sw"});
      }*/

      //returns the id of the next video
      function loadNextVideo(){
        if(soFarPlayed > videoIDs.length){
            return null;
        }else{
            return videoIDs[soFarPlayed++];
        }

      }

      function getSongs(){
        //make ajax call back with list to return song data belonging to the videos


      }


    </script>



@stop
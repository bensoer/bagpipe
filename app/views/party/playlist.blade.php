@extends('layouts.main')

@section('meta-title', 'Playlist')
@stop

@section('content')

 <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div class="container" style="min-height: 350px;">

        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="col-lg-12 center-block">
                     <div class="form-group prev_next">

                            <h1>Now Playing</h1>
                            <p id="label"></p></p>
                            <div id="player"></div>

                     </div>
                 </div>
             </div>
        </div>

        <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="col-lg-12 center-block">
                             <div class="form-group" style="border: 2px solid black;text-align:center">
                                  <div id="prev_next">
                                        <button type="button" class="btn btn-default" style="float:left" onclick="goToPrevious()">Play Previous</button>

                                        <h1 style="display:inline">Up Next</h1>

                                        <button type="button" class="btn btn-default" style="float:right" onclick="goToNext()">Play Next</button>
                                  </div>


                                  <div id="next"><ul id="list" style="list-style-type:none"></ul></div>

                                  <div class="row">
                                      <div class="col-md-4 col-md-offset-4" style="border: 2px solid red;">


                                             <form class="form-signin" role="form"  method="POST" id="searchSong">
                                                 <h2 class="form-signin-heading">Add To The List</h2>
                                                 <div>
                                                    <input type="text" class="form-control" name="search" id="search" required autofocus>

                                                    <button class="btn btn-lg btn-primary btn-block" type="submit" >Search</button>
                                                </div>

                                             </form>
                                      </div>
                                  </div>
                                  <div id="new_search"><ul id="search_list" style="list-style-type:none;text-align:center"></ul></div>



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

    <script>


        <?php $videoIDs = $videoData['videoIDs'];
        $videoNames = $videoData['videoNames']; ?>
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
        //make ajax call back with list to return song data belonging to the videos




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
            alert("Called");
            event.preventDefault();


            var $form = $( this ),
                data = $form.serialize(),
                url = "/searchSong";


            var posting = $.post( url, { formData: data } );

            posting.done(function(results){
                if(results.success){
                    window.alert("SUCCESSSSSS" + results.data);

                    var list = document.getElementById('search_list');
                    list.innerHTML = '';


                    for (var i=soFarPlayed+1; i < results.data[1].length; i++){

                        var node=document.createElement("li");
                        var textnode=document.createTextNode(results.data[1][i]);
                        node.appendChild(textnode);
                        document.getElementById("search_list").appendChild(node);
                     }


                }else{
                    window.alert("FAAIAILLUURREEEE");
                }
            });
         });




    </script>



@stop
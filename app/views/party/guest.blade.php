@extends('layouts.party')

@section('meta-title', 'Guest')
@stop

@section('navies')

<li id="token-box" class="page-scroll">
    <div class="input-group">
        <!-- TODO: when user joins, return with input update on join, refresh queue list -->
        <input type="text" class="form-control" maxlength="11" size="11"
               title="Join" placeholder="CODE HERE"
               value="<?php echo $data['token']; ?>">
        <span class="input-group-btn">
            <button class="btn btn-success" type="button">Join!</button>
        </span>
    </div>
</li>

@stop


@section('search')

<div id="searchbox">
    <form class="navbar-form navbar-left" role="search" method="POST" id="searchSong">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search" name="search" id="search">
              <p hidden id="session_token"><?php echo $data['token']; ?></p>
            </div>
    </form>
</div>

@stop

@section('control')


<!-- Currently playing -->
<div id="current-song">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">
                 <div class="form-group prev_next">

                        <h2>Now Playing</h2>
                        <!-- JavaScript  and PHP loaded now playing list -->
                        <p id="label">
                            <a href="https://www.youtube.com/watch?v=<?php $songlist = $data['songlist'];
                               echo $songlist[0]->songid; ?>"
                               target="_blank">
                               <?php $songlist = $data['songlist']; echo $songlist[0]->songname ?>
                            </a>
                        </p>

                 </div>
             </div>
         </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">
                 <div class="form-group prev_next">

                        <div class="progress">
                          <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
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
                            <?php $songlist = $data['songlist'];
                                for ($i = 1; $i < count($songlist); $i++){ ?>
                                <li class="queue-item">
                                    <div class="row">
                                        <!-- YT Thumbnail and title -->
                                        <div class="col-lg-8">
                                            <div class="media">
                                                <a class="media-left queue-thumb"
                                                   href="https://www.youtube.com/watch?v=<?php echo $songlist[$i]->songid; ?>" target="_blank">
                                                    <img src="http://img.youtube.com/vi/<?php echo $songlist[$i]->songid; ?>/hqdefault.jpg">
                                                </a>
                                                <div class="media-body media-middle">
                                                    <a href="https://www.youtube.com/watch?v=<?php echo $songlist[$i]->songid; ?>" target="_blank">
                                                        <span class="media-heading"><?php echo $songlist[$i]->songname; ?></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Votes and upvote button -->
                                        <div id="voting" class="btn-group pull-right" role="group" aria-label="...">
                                          <button type="button" class="btn btn-default votes" disabled>
                                            <span class="votes-number"><?php echo $songlist[$i]->votes ; ?></span>
                                          </button>
                                          <button type="button" class="btn btn-default upvote">
                                            <span class="upvote-icon glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                                          </button>
                                        </div>
                                    </div>
                                </li>
                           <?php } ?>
                        </ul>
                  </div>
             </div>
        </div>
    </div>
</div>

<script>
    var rate = 5 * 1000;
    window.setTimeout(getCurrentlyPlaying, rate);
    window.setTimeout(getUpNext, rate);


    function getCurrentlyPlaying(){
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
    }

    function getUpNext(){
       var token = document.getElementById("session_token");
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
                list.innerHTML = '';

               for(var i = 0; i < json[0].length ; i++){



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
                            thumbLink.href="https://www.youtube.com/watch?v=" + json[1][i];

                                var thumbImg = document.createElement("img");
                                thumbImg.src = "http://img.youtube.com/vi/" + json[1][i] + "/hqdefault.jpg";
                                thumbImg.style.height = "50px";
                                thumbImg.style.width = "67px";
                                thumbImg.style.verticalAlign = "middle";

                            thumbLink.appendChild(thumbImg);

                            var mediaBodyDiv = document.createElement("div");
                            mediaBodyDiv.className = "media-body media-middle";
                            mediaBodyDiv.target = "_blank";

                                var mediaBodyLink = document.createElement("a");
                                mediaBodyLink.href="https://www.youtube.com/watch?v=" + json[1][i];

                                    var mediaBodySpan = document.createElement("span");
                                    mediaBodySpan.className = "media-heading";
                                        var songName = document.createTextNode(json[0][i]);
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
                            var votingSpan = document.createElement("span");
                            votingSpan.className = "votes-number";
                                var voteNum = document.createTextNode(json[2][i]);
                            votingSpan.appendChild(voteNum);
                        votingBtn.appendChild(votingSpan);

                        var upVoteBtn = document.createElement("button");
                        upVoteBtn.type = "button";
                        upVoteBtn.className = "btn btn-default upvote";
                            var upVoteSpan = document.createElement("span");
                            upVoteSpan.className = "upvote-icon glyphicon glyphicon-chevron-up";
                            upVoteSpan.setAttribute("aria-hidden", "true");
                        upVoteBtn.appendChild(upVoteSpan);

                    votingDiv.appendChild(votingBtn);
                    votingDiv.appendChild(upVoteBtn);

                    rowDiv.appendChild(tntDiv);
                    rowDiv.appendChild(votingDiv);

                    listItem.appendChild(rowDiv);







                    //var textnode = document.createTextNode(json[i]);
                    //listItem.appendChild(textnode);
                    document.getElementById("list").appendChild(listItem);

               }
           }

           //var lbl = document.getElementById("label");
           //lbl.innerHTML = result.name;
       });

    }

</script>
@stop
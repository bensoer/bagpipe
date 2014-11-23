@extends('layouts.party')

@section('meta-title', 'Guest')
@stop

@section('navies')
<!--
<li class="page-scroll">
    <span id="sharelabel">Share</span>
</li>
<li class="page-scroll">
    <div id="sharecode">

        <span >AA098</span>
    </div>
</li>
-->
@stop


@section('search')

<div id="searchbox">
    <form class="navbar-form navbar-left" role="search" method="POST" id="searchSong">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search" name="search" id="search">
              <p hidden id="token"><?php echo $data['token']; ?></p>
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
                        <!-- JavaScript  nad PHP loaded now playing list -->
                        <p id="label"><?php $songlist = $data['songlist']; echo $songlist[0]->songname ?></p>


                 </div>
             </div>
         </div>
    </div>
</div>

<!-- Queue of YT videos -->
<div id="queue-list">
    <div class="row">
        <div class="col-lg-12 text-center">
                 <div class="form-group" style="border: 2px solid black;text-align:center">
                            <h2>Up Next</h2>
                            <ul>
                                <li class="queue-item">
                                    Video 1
                                </li >

                                <li class="queue-item">
                                    Video 2
                                </li>
                                <li class="queue-item">
                                    Video 3
                                </li>

                            </ul>



                        <!-- JavaScript and PHP loaded up next list-->
                      <div id="next">
                            <ul id="list" class="list-group" style="list-style-type:none">
                                <?php $songlist = $data['songlist'];
                                    for ($i = 1; $i < count($songlist); $i++){ ?>
                                    <li class="queue-item"><?php echo $songlist[$i]->songname ?></li>

                               <?php } ?>
                            </ul>
                      </div>

                 </div>
        </div>
    </div>
</div>

<script>
    var rate = 5 * 1000;
    window.setInterval(getCurrentlyPlaying, rate);
    window.setInterval(getUpNext, rate);


    function getCurrentlyPlaying(){
        var token = document.getElementById("token");
        var json = {"session_token": token.innerHTML};

        var data = JSON.stringify(json);
        var url = "/getCurrent";

        var post = $.post(url, {formData: data});

        post.done(function(result){
            var lbl = document.getElementById("label");
            lbl.innerHTML = result.name;
        });
    }

    function getUpNext(){
       var token = document.getElementById("token");
       var json = {"session_token": token.innerHTML};

       var data = JSON.stringify(json);
       var url = "/getUpNext";

       var post = $.post(url, {formData: data});

       post.done(function(result){
            var json = JSON.parse(result);
            //alert(json[0] + "\n" + json.length);
            document.getElementById('list').innerHTML = "";

           for(var i = 0; i< json.length ; i++){
                var listItem=document.createElement("li");
                listItem.className = "gueue-item";
                listItem.innerHTML = json[i];


                //link.appendChild(textnode);

                document.getElementById("list").appendChild(listItem);
           }

           //var lbl = document.getElementById("label");
           //lbl.innerHTML = result.name;
       });

    }

</script>
@stop
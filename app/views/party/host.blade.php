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
              <input type="text" class="form-control" placeholder="Search Youtube" name="search" id="search">
              <p id="session_token" hidden>{{ $data['shareCode'] }}</p>
            </div>
    </form>
</div>

<!-- Search Results -->
<div class = "row">

    <div class="form-group">

        <!-- JavaScript loaded search list. Note: changes to list style need to be applied in JavaScript -->
        <div id="search_list" id="animate fadeInDown" class="list-group" data-toggle="items">


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
                            <span id="play" class="glyphicon glyphicon-play"></span>
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
                                                    <img src="https://img.youtube.com/vi/<?php //echo $songlist[$i]->songid; ?>/hqdefault.jpg">
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








<!-- AJAX/JAVASCRIPT/YOUTUBE API -->
<script src="{{asset("js/host-functions.js")}}"></script>


@stop
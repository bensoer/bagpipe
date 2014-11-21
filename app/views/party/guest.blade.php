@extends('layouts.party')

@section('meta-title', 'Guest')
@stop

@section('navies')

<li class="page-scroll">
    <span id="sharelabel">Share</span>
</li>
<li class="page-scroll">
    <div id="sharecode">
        <!-- TODO: Sharecode goes here -->
        <span >AA098</span>
    </div>
</li>

@stop

@section('control')

<!-- Currently playing -->
<div id="current-song">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">
                 <div class="form-group prev_next">

                        <!-- JavaScript loaded now playing list -->
                        <p id="label"></p>
                        <!-- Youtube player module -->
                        <div id="player"></div>

                 </div>

                     <button type="button" class="btn btn-default" style="float:left" onclick="goToPrevious()">Play Previous</button>
                     <button type="button" class="btn btn-default" style="float:left" onclick="stopVideo()">Stop Playlist</button>
                     <button type="button" class="btn btn-default" style="float:right" onclick="goToNext()">Play Next</button>
                     <button type="button" class="glyphicon glyphicon-play" aria-hidden="true" style="float:right" onclick="playVideo()">Play/Resume Playlist</button>
                     <span class="glyphicon glyphicon-play-circle" aria-hidden="true" onclick="playVideo()"></span>

             </div>
         </div>
    </div>
</div>

<!-- Queue of YT videos -->
<div id="queue-list">
    <div class="row">
        <div class="col-lg-12 text-center">
                 <div class="form-group" style="border: 2px solid black;text-align:center">

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



                        <!-- JavaScript loaded up next list-->
                      <div id="next"><ul id="list" class="list-group" style="list-style-type:none"></ul></div>

                 </div>
        </div>
    </div>
</div>


@stop
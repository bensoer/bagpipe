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


@stop
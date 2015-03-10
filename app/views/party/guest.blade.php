@extends('layouts.party')

@section('meta-title', 'Guest')
@stop

@section('navies')

<li id="token-box" class="page-scroll">
    <div class="input-group">
        <form action="/guest" method="POST">
            <!-- TODO: when user joins, return with input update on join, refresh queue list -->
            <input type="text" class="form-control" maxlength="13" size="13"
                   title="Join" placeholder="CODE HERE"
                   value="<?php echo $data['token']; ?>" name="party_search">
            <span class="input-group-btn">
                <button class="btn btn-success" type="submit">Join!</button>
            </span>
        </form>
    </div>
</li>

@stop


@section('search')

<div id="searchbox">
    <form class="navbar-form navbar-left" role="search" method="POST" id="searchSong">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search Youtube" name="search" id="search">
              <p hidden id="session_token"><?php echo $data['token']; ?></p>
            </div>

    </form>
</div>

<!-- Search Results -->
<div class = "row">
    <div class="form-group">

        <!-- JavaScript loaded search list. Note: changes to list style need to be applied in JavaScript -->
        <div id="search_list" class="list-group"  data-toggle="items" style="text-align:left">


        </div>
    </div>
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
                                               <!-- <a class="media-left queue-thumb"
                                                   href="https://www.youtube.com/watch?v=<?php //echo $songlist[$i]->songid; ?>" target="_blank">
                                                    <img src="http://img.youtube.com/vi/<?php //echo $songlist[$i]->songid; ?>/hqdefault.jpg">
                                                </a>-->
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
                                          <button type="button" class="btn btn-default upvote"
                                            id="<?php echo $songlist[$i]->songid; ?>Button"
                                            value="<?php echo $songlist[$i]->songid; ?>">
                                            <span class="upvote-icon glyphicon glyphicon-chevron-up" aria-hidden="true"
                                                id="<?php echo $songlist[$i]->songid; ?>Icon" onclick="submitVote()"
                                                value="<?php echo $songlist[$i]->songid; ?>"></span>
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

<script src="{{asset("js/guest-functions.js")}}"></script>

@stop
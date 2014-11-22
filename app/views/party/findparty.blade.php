@extends('layouts.party')

@section('meta-title', 'Guest')
@stop

@section('navies')

@stop

@section('search')
<h3>Find A Party</h3>
<div id="searchbox">

<form class="navbar-form navbar-left" role="search" method="POST" action="/guest" id="searchSong">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Enter the Party's Share Token Here" name="party_search" id="party_search">
        </div>
    </form>
</div>
@stop

@section('control')



@stop
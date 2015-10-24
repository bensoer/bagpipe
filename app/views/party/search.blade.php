@extends('layouts.main')

@section('meta-title', 'About')
@stop

@section('content')


<div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">

            @foreach($embedded as $vid){{
                 "<button type=\"button\" class=\"btn btn-default\">" . $vid ." <br>Add To Playlist </button>"
            }}@endforeach
            </div>
        </div>
</div>


@stop
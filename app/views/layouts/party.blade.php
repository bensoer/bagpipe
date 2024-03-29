@extends('layouts.main')

@section('content')

<!-- Header Bar -->
<nav class="navbar navbar-default navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">BAGPIPE</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                @yield('navies')
            </ul>
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>

<!-- search box -->
<section id="search-hub">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            @yield('search')
        </div>
    </div>
</div>
</section>

<!-- control box -->
<section id="control-hub">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div id="controlbox">
                @yield('control')
            </div>
        </div>
    </div>
</div>
</section>


@stop
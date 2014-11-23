<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bagpipe - @yield('meta-title')</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/apple-touch-icon-57x57.png') }}" >
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/apple-touch-icon-114x114.png') }}" >
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/apple-touch-icon-72x72.png') }}" >
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/apple-touch-icon-144x144.png') }}" >
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/apple-touch-icon-60x60.png') }}" >
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/apple-touch-icon-120x120.png') }}" >
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/apple-touch-icon-76x76.png') }}" >
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/apple-touch-icon-152x152.png') }}" >
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon-180x180.png') }}" >
    <link rel="icon" type="image/png" href="{{ asset('/favicon-192x192.png" sizes="192x192') }}" >
    <link rel="icon" type="image/png" href="{{ asset('/favicon-160x160.png" sizes="160x160') }}" >
    <link rel="icon" type="image/png" href="{{ asset('/favicon-96x96.png" sizes="96x96') }}" >
    <link rel="icon" type="image/png" href="{{ asset('/favicon-16x16.png" sizes="16x16') }}" >
    <link rel="icon" type="image/png" href="{{ asset('/favicon-32x32.png" sizes="32x32') }}" >
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('font-awesome-4.1.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

    <!-- jQuery -->
        <script src="{{ asset('js/jquery.js') }}"></script>

</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="fa fa-bars"></i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            <li class="sidebar-brand">
                <a href="/">Bagpipe</a>
            </li>
            <li>
                <a href="/">Home</a>
            </li>
            <li>
                <a href="/about">About</a>
            </li>
            <li>
                <a href="/login">Login</a>
            </li>
            <li>
                <a href="/register">Register</a>
            </li>
            <li>
                <a href="/#contact">Contact</a>
            </li>
        </ul>
    </nav>

    {{-- Content--}}
    @yield('content')

    <!-- Footer -->
    <footer>
        <div id="contact" class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <a href="/"> <h4><strong>Bagpipe</strong></h4></a>
                    <br />
                    <ul class="list-inline">
                        <li>
                            <a target="_blank" href="https://github.com/bensoer/bagpipe"><i class="fa fa-github fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://twitter.com/hashtag/bcitbagpipe"><i class="fa fa-twitter fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-youtube fa-fw fa-3x"></i></a>
                        </li>
                    </ul>
                    <hr class="small">
                    <ul class="list-unstyled">
                        <li><i class="glyphicon glyphicon-send" aria-hidden="true"></i>&nbsp;
                            Created by
                            <a target="_blank" href="http://www.bensoer.com/">Ben</a>
                            and
                            <a target="_blank" href="https://www.ryansadio.com/">Ryan</a>
                        </li>
                    </ul>
                    <p class="text-muted">Copyright &copy; Bagpipe 2014</p>
                </div>
            </div>
        </div>
    </footer>



    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>

</body>

</html>
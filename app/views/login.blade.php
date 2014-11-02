<!-- app/views/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/login.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">


        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#login" role="tab" data-toggle="tab" id="tab">Login</a></li>
            <li role="presentation"><a href="#create_account" role="tab" data-toggle="tab" id="tab">Create Account</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="login">

                <form class="form-signin" role="form" action="/" method="POST">
                    <h2 class="form-signin-heading">Login</h2>
                    <input type="email" class="form-control" name="email" value="{{Input::old('email')}}" placeholder="Email address" required autofocus>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="remember-me"> Remember me
                        </label>
                    </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                <span style="color:darkred">{{"<br>" . $errors->first('email') . $errors->first('password') }}</span>
                <span style="color:darkred">@if(Session::has('message')){{Session::get('message')}}@endif</span>
                </form>

            </div>
            <div role="tabpanel" class="tab-pane fade" id="create_account">
                Create an Account Here...
            </div>

        </div>








    </div> <!-- /container -->
    <script src="js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
  </body>
</html>



<!--
<!doctype html>
<html>
<head>
	<title>Look at me Login</title>
</head>
<body>

	{{ Form::open(array('url' => 'login')) }}
		<h1>Login</h1>

		<!-- if there are login errors, show them here --><!--
		<p>
			{{ $errors->first('email') }}
			{{ $errors->first('password') }}
		</p>

		<p>
			{{ Form::label('email', 'Email Address') }}
			{{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
		</p>

		<p>
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password') }}
		</p>

		<p>{{ Form::submit('Submit!') }}</p>
	{{ Form::close() }}

</body>
</html>
-->
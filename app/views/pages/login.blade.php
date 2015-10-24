@extends('layouts.main')

@section('meta-title', 'Login')
@stop

@section('content')


<div class="container" style="min-height: 350px;">

    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="col-lg-12 center-block">
                 <div class="form-group">
                    <div class="col-xs-4 col-lg-offset-4">


                           <form class="form-signin" role="form" action="/login" method="POST">
                               <h2 class="form-signin-heading">Login</h2>
                               <input type="email" class="form-control" name="email" value="{{Input::old('email')}}" placeholder="Email address" required autofocus>
                               <input type="password" class="form-control" name="password" placeholder="Password" required>

                           <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                           <!--<div class="checkbox" style="float:left">
                                <label>
                                    <input type="checkbox" value="remember-me"> Remember me
                                </label>
                           </div>-->
                           <a href="/forgotpassword">Forgot Password</a>
                           <span style="color:darkred">{{"<br>" . $errors->first('email') . $errors->first('password') }}</span>
                           <span style="color:darkred">@if(Session::has('error')){{Session::get('error')}}@endif</span>
                           </form>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>


@stop
@extends('layouts.main')

@section('meta-title', 'Host')
@stop

@section('content')

<div class="container" style="min-height: 350px;">

    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>I am a host!</h1>
            Invite your crowd: <code>AX99B</code></p>

            <div class="col-lg-12 center-block">
                {{ Form::open(['role' => 'form']) }}

                    <div class="form-group">
                        <div class="col-xs-2 col-lg-offset-5">
                            {{ Form::text('search', NULL, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter name of song', 'aria-title' => 'Search'], 'autofocus') }}
                            {{ $errors->first('search', '<span class="error">:message</span>'); }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-2 col-lg-offset-5">
                            {{ Form::submit('Search', ['class' => 'form-control']) }}
                        </div>
                    </div>

                {{ Form::close() }}
            </div>

            <ul class="list-unstyled">
                <li>Taylor Swift - 1989</li>
                <li>James Blunt - You're Beautiful</li>
            </ul>
        </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

@stop
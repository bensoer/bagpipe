@extends('layouts.main')

@section('meta-title', 'Guest')
@stop

@section('content')

<div class="container" style="min-height: 350px;">

    <div class="row">
        <div class="col-lg-12 text-center">
            <h1>I am a guest!</h1>

            <div class="col-lg-12 center-block">
                {{ Form::open(['role' => 'form']) }}

                    <div class="form-group">
                        <div class="col-xs-2 col-lg-offset-5">
                            {{ Form::text('code', NULL, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter code here', 'aria-title' => 'Code'], 'autofocus') }}
                            {{ $errors->first('code', '<span class="error">:message</span>'); }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-2 col-lg-offset-5">
                            {{ Form::submit('Join!', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    
                {{ Form::close() }}
            </div>


            <br />
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
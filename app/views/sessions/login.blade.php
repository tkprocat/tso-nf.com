@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
Log In
@stop

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-md-5 col-md-offset-3">
        {{ Form::open(array('action' => 'SessionController@store')) }}

            <h2 class="form-signin-heading">Sign In</h2>

            <div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}">
                {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username', 'autofocus')) }}
                {{ ($errors->has('username') ? $errors->first('username') : '') }}
            </div>

            <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password'))}}
                {{ ($errors->has('password') ?  $errors->first('password') : '') }}
            </div>
            
			<div class="form-group">
					{{ Form::checkbox('rememberMe', 'rememberMe') }} Remember me
			</div>
            {{ Form::submit('Sign In', array('class' => 'btn btn-primary'))}}
            <a class="btn btn-default" href="{{ route('forgotPasswordForm') }}">Forgot Password</a>
            <a class="btn btn-default" href="{{ URL::to('register') }}">Create new account</a>
        {{ Form::close() }}
    </div>
</div>

@stop
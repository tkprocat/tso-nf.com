@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
	<h4>Account Profile</h4>
	
  	<div class="well clearfix">
	    <div class="col-md-8">
		    @if ($user->username)
		    	<p><strong>Username:</strong> {{ $user->username }} </p>
			@endif
			@if ($user->guildname)
		    	<p><strong>Guild:</strong> {{ $user->guildname }} </p>
			@endif
		    <p><strong>Email:</strong> {{ $user->email }}</p>

			<p><em>Account created: {{ $user->created_at }}</em></p>
			<p><em>Last Updated: {{ $user->updated_at }}</em></p>
			<button class="btn btn-primary" onClick="location.href='{{ action('UserController@edit', array($user->id)) }}'">Edit Profile</button>
		</div>
	</div>

	<h4>Group Memberships:</h4>
	<div class="well">
	</div>
@stop

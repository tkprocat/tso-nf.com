@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Register
@stop

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        {{ Form::open(array('action' => 'GuildApplicationController@store')) }}

        <h2>Join guild {{ $guild->name }}</h2>

        {{ Form::hidden('guild_id', $guild->id) }}

        <div style="text-align: center">{{ Form::submit('Send application', array('class' => 'btn btn-primary')) }}
        </div>

        {{ Form::close() }}
    </div>
</div>
@stop
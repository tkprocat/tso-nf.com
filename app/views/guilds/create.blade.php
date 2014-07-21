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
        {{ Form::open(array('action' => 'GuildController@store')) }}

            <h2>Create new guild</h2>

            <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Guild name')) }}
                {{ ($errors->has('name') ? $errors->first('name') : '') }}
            </div>

            <div class="form-group {{ ($errors->has('tag')) ? 'has-error' : '' }}">
                {{ Form::text('tag', null, array('class' => 'form-control', 'placeholder' => 'Tag')) }}
                {{ ($errors->has('tag') ? $errors->first('tag') : '') }}
            </div>

            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
            
        {{ Form::close() }}
    </div>
</div>
@stop
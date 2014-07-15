@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Edit Profile
@stop

{{-- Content --}}
@section('content')

<h4>Edit guild</h4>
<div class="well">
    {{ Form::open(array(
    'action' => array('GuildController@update', $guild->id),
    'method' => 'put',
    'class' => 'form-horizontal',
    'role' => 'form'
    )) }}

    <div class="form-group {{ ($errors->has('tag')) ? 'has-error' : '' }}" for="tag">
        {{ Form::label('edit_tag', 'Tag', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-2">
            {{ Form::text('tag', $guild->tag, array('class' => 'form-control', 'placeholder' => 'Tag', 'id' =>
            'edit_tag'))}}
        </div>
        {{ ($errors->has('tag') ? $errors->first('tag') : '') }}
    </div>

    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}" for="name">
        {{ Form::label('edit_name', 'Name', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('name', $guild->name, array('class' => 'form-control', 'placeholder' => 'Name',
            'id' => 'edit_name'))}}
        </div>
        {{ ($errors->has('name') ? $errors->first('name') : '') }}
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::hidden('id', $guild->id) }}
            {{ Form::submit('Update', array('class' => 'btn btn-primary'))}}
        </div>
    </div>
    {{ Form::close()}}
</div>
@stop
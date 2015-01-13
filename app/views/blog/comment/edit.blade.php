@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Edit Profile
@stop

{{-- Content --}}
@section('content')

<h4>Edit blog post</h4>
<div class="well">
    {{ Form::open(array(
    'url' => 'blog/comment/'.$comment->id,
    'method' => 'put',
    'class' => 'form-horizontal',
    'role' => 'form'
    )) }}

    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}" for="content">
        {{ Form::label('edit_content', 'Content', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::textarea('content', $comment->content, array('class' => 'form-control', 'placeholder' => 'Content',
            'id' => 'edit_content'))}}
        </div>
        {{ ($errors->has('content') ? $errors->first('content') : '') }}
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::hidden('id', $comment->id) }}
            {{ Form::submit('Update', array('class' => 'btn btn-primary'))}}
        </div>
    </div>
    {{ Form::close()}}
</div>
@stop
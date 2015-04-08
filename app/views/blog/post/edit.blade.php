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
    'url' => 'blog/update',
    'method' => 'put',
    'class' => 'form-horizontal',
    'role' => 'form'
    )) }}

    <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}" for="title">
        {{ Form::label('edit_title', 'Title', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('title', $blog->title, array('class' => 'form-control', 'placeholder' => 'Title', 'id' =>
            'edit_Title'))}}
        </div>
        {{ ($errors->has('title') ? $errors->first('title') : '') }}
    </div>

    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}" for="content">
        {{ Form::label('edit_content', 'Content', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::textarea('content', $blog->content, array('class' => 'form-control', 'placeholder' => 'Content',
            'id' => 'edit_content'))}}
        </div>
        {{ ($errors->has('content') ? $errors->first('content') : '') }}
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::hidden('id', $blog->id) }}
            {{ Form::submit('Update', array('class' => 'btn btn-primary'))}}
        </div>
    </div>
    {{ Form::close()}}
</div>
<script type="text/javascript" src="{{ URL::to('/') }}/assets/js/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        plugins : "bbcode link image textcolor colorpicker",
        toolbar1: "bold italic underline | link unlink image | blockquote | removeformat | forecolor emoticons",
        menubar: false,
        statusbar : false
    });
</script>
@stop
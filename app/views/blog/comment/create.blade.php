@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Create new comment
@stop

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-md-12">
        {{ Form::open(array('action' => 'BlogCommentController@store')) }}

            <h2>Post a comment</h2>

            <div class="form-group {{ ($errors->has('Content')) ? 'has-error' : '' }}">
                {{ Form::textarea('content', null, array('class' => 'form-control', 'placeholder' => 'Content goes here!')) }}
                {{ ($errors->has('content') ? $errors->first('content') : '') }}
            </div>
            {{ Form::hidden('post_id', $post_id) }}
            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
            
        {{ Form::close() }}
    </div>
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
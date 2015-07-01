@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Create new post
@stop

{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="/blog" accept-charset="UTF-8">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <h2>Create new blog post</h2>

            <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
                <input class="form-control" placeholder="Fancy title" name="title">
                {{ ($errors->has('title') ? $errors->first('title') : '') }}
            </div>

            <div class="form-group {{ ($errors->has('Content')) ? 'has-error' : '' }}">
                <textarea class="form-control" placeholder="Content goes here!" name="content" cols="50" rows="10"></textarea>
                {{ ($errors->has('content') ? $errors->first('content') : '') }}
            </div>

            <input class="btn btn-primary" type="submit" value="Create">
        </form>
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
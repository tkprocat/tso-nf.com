@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-md-12">
        <form method="POST" action="/blog/{{ $post_id }}/comment" accept-charset="UTF-8">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">

            <h2>Post a comment</h2>

            <div class="form-group {{ ($errors->has('Content')) ? 'has-error' : '' }}">
                <textarea class="form-control" placeholder="Content goes here!" name="content" cols="50" rows="10"></textarea>
                {{ ($errors->has('content') ? $errors->first('content') : '') }}
            </div>
            <input type="hidden" name="post_id"  value="{{ $post_id }}">
            <input class="btn btn-primary" type="submit" value="Create">
        </form>
    </div>
</div>
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
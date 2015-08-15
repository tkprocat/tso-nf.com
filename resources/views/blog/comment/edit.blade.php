@extends('layouts.default')

@section('content')
    <h4>Edit blog post</h4>
    <div class="well">
        <form method="POST" action="/blog/{{ $comment->post->id }}/comment/{{ $comment->id }}" accept-charset="UTF-8"
              class="form-horizontal">
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">

            <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}" for="content">
                <label for="content" class="col-sm-2 control-label">Content</label>

                <div class="col-sm-10">
                    <textarea class="form-control" placeholder="Content" name="content" cols="50" rows="10">{!! old('content', $comment->content) !!}</textarea>
                </div>
                {{ ($errors->has('content') ? $errors->first('content') : '') }}
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit" value="Update">
                </div>
            </div>
        </form>

    </div>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            plugins: "bbcode link image textcolor colorpicker",
            toolbar1: "bold italic underline | link unlink image | blockquote | removeformat | forecolor emoticons",
            menubar: false,
            statusbar: false
        });
    </script>
@stop
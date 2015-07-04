@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit blog post</div>
                    <div class="panel-body">
                        <form method="POST" action="/blog/{{ $blog->id }}" accept-charset="UTF-8"
                              class="form-horizontal" role="form">
                            <input name="_method" type="hidden" value="PUT">
                            {!! csrf_field() !!}

                            @include('errors.list')

                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">Title</label>

                                <div class="col-sm-10">
                                    <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                                           class="form-control" placeholder="Title">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content" class="col-sm-2 control-label">Content</label>

                                <div class="col-sm-10">
                                    <textarea name="content" class="form-control" placeholder="Content" cols="50"
                                              rows="10">{{ old('content', $blog->content) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" class="btn btn-primary" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ URL::to('/') }}/tinymce/tinymce.min.js"></script>
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
@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<h4>News:</h4>

@foreach($blogs as $blog)
<div class="panel panel-default">
    <div class="panel-heading">
        <a href="{{ URL::to('blog/'.$blog->slug) }}">{{ $blog->title }}</a><br>
        Posted by {{ $blog->user->username }} at {{ $blog->created_at }}
        @if ($blog->updated_at > $blog->created_at)
            - Last updated at {{ $blog->updated_at }}
        @endif
    </div>
    <div class="panel-body">
        <p>{{ BBCode::parse($blog->content) }}</p>
    </div>
    <div class="panel-footer" style="height: 55px; line-height: 35px">
        <div class="col-md-6" style="vertical-align: middle;">
            <a href="{{ URL::to('blog/'.$blog->slug) }}">
            @if (count($blog->comments) == 0)
                No comments
            @elseif (count($blog->comments) == 1)
                1 comment
            @else
                {{ count($blog->comments) }} comments
            @endif
            </a>
        </div>
        @if ((Sentry::getUser()) && (Sentry::hasAccess('admin')))
        <div class="col-md-6" style="height: 80%; text-align: right">
            <a href="{{ URL::to('blog/'.$blog->id.'/edit') }}" class="btn btn-warning">Edit</a>
            <a href="#" class="btn btn-danger deleteBlogPostBtn" data-target="{{ $blog->id }}">Delete</a>
        </div>
        @endif
    </div>
</div>
@endforeach

@if ((Sentry::getUser()) && (Sentry::hasAccess('admin')))
<a href="{{ URL::to('blog/create') }}" class="btn btn-primary">Create new blog post.</a>
@include('blog.post.partials.delete')
@endif
@stop

@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ $post->title }}</div>
    <div class="panel-body">
        {{ BBCode::parse(nl2br($post->content)) }}
    </div>
    <div class="panel-footer">Posted by {{ $post->username }} at {{ $post->created_at }}
        @if ($post->updated_at > $post->created_at )
        - Last updated at {{ $post->updated_at }}
        @endif
    </div>
</div>


<a href="{{ URL::route('blog') }}" class="btn btn-primary">Back</a>
@stop
<h4>Comments</h4>

@foreach($comments as $comment)
<div class="panel panel-default">
    <div class="panel-body">
        {{ BBCode::parse($comment->content) }}
    </div>
    <div class="panel-footer" style="height: 55px">
        <div class="col-md-6" style="height: 80%; vertical-align: middle">
            Posted by {{ $comment->username }} at {{ $comment->created_at }}
            @if ($comment->updated_at > $comment->created_at)
            - Last updated at {{ $comment->updated_at }}
            @endif
        </div>
        @if (Sentry::check())
            @if (((Sentry::getUser()) && (Sentry::hasAccess('admin')) || (Sentry::getUser()->id == $comment->user_id)))
            <div class="col-md-6" style="height: 80%; text-align: right">
                <a href="{{ URL::to('blog/comment/'.$comment->id.'/edit') }}" class="btn btn-warning">Edit</a>
            </div>
            @endif
        @endif
    </div>
</div>
@endforeach

@if ($comments->count() == 0)
<p>Nobody has posted a comment yet.</p>
@endif
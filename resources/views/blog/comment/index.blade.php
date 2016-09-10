<h4>Comments</h4>

@foreach($comments as $comment)
<div class="panel panel-default">
    <div class="panel-body">
        {{ BBCode::parse($comment->content) }}
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-6" style="vertical-align: middle">

                Posted by {{ $comment->user->username }} at {{ $comment->created_at }}
                @if ($comment->updated_at > $comment->created_at)
                - Last updated at {{ $comment->updated_at }}
                @endif
            </div>
            @if (Auth::check())
                @if (((Entrust::can('admin-blog')) || (Auth::user()->id == $comment->user_id)))
                <div class="col-md-6" style="height: 80%; text-align: right">
                    <a href="{{ URL::to('blog/'.$comment->post_id.'/comment/'.$comment->id.'/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger deleteBlogCommentBtn" data-target="{{ $comment->id }}">Delete</a>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endforeach

@if ($comments->count() == 0)
<p>Nobody has posted a comment yet.</p>
@endif

@include('blog.comment.partials.delete')
@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<h4>Guilds:</h4>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover sortable" id="users">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Tag</th>
                    <th>Members</th>
                    <th>Leader</th>
                    @if ((Sentry::check()) && (Sentry::hasPermission('admin')))
                    <th></th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($guilds as $guild)
                <tr>
                    <td><a href="{{ URL::to('/guilds/'.$guild->id) }}">{{ $guild->name }}</a></td>
                    <td>{{ $guild->tag }}</td>
                    <td>{{ $guild->members()->count() }}</td>
                    <td>{{ $guild->guildleader }}</td>
                    <td>
                    <a href="{{ URL::to('/guilds/applications/create/'.$guild->id) }}" class="btn btn-primary">Join</a>
                    @if ((Sentry::check()) && (Sentry::hasPermission('admin')))
                    <a href="{{ URL::to('/guilds/'.$guild->id.'/edit') }}" class="btn btn-info">Edit</a>
                    <a href="{{ URL::to('/guilds/'.$guild->id.'/delete') }}" class="btn btn-danger">Delete</a>
                    @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                @if ((Sentry::check()) && (Sentry::hasPermission('admin')))
                <tfoot>
                    <tr><td colspan="5"><a href="{{ URL::to('/guilds/create') }}" class="btn btn-primary">Add new guild</a></td></tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
<script>
</script>

@stop
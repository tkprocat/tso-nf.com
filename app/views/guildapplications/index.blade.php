@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<h4>Current Users:</h4>
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
                    @if (Sentry::hasPermission('admin'))
                    <th>Action</th>
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
                    @if (Sentry::hasPermission('admin'))
                    <td><a href="{{ URL::to('/guilds/'.$guild->id.'/edit') }}">Edit</a>
                    <a href="{{ URL::to('/guilds/'.$guild->id.'/delete') }}">Delete</a></td>
                    @endif
                </tr>
                @endforeach
                </tbody>
                @if (Sentry::hasPermission('admin'))
                <tfoot>
                    <tr><td colspan="5"><a href="{{ URL::to('/guilds/create') }}">Add new guild</a></td></tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
<script>
</script>

@stop
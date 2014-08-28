@extends('layouts.default')

{{-- Content --}}
@section('content')
<h4>{{ $guild->name }}</h4>

<table class="table table-striped table-hover sortable">
    <thead>
    <tr>
        <th>Username</th>
        <th>Rank</th>
        @if (Sentry::hasPermission('admin') || Sentry::hasAccess('Guild_'.$guild->tag.'_Admins'))
        <th></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($admins as $admin)
    <tr>
        <td>{{ $admin->username }}</td>
        <td>Admin</td>
        @if (Sentry::hasAccess('admin') || Sentry::hasAccess('Guild_'.$guild->tag.'_Admins'))
        <td><a href="{{ URL::to('/guilds/'.$guild->id.'/demote/'.$admin->id) }}">Demote</a></td>
        @endif
    </tr>
    @endforeach

    @foreach($members as $member)
        @if (!$guild->isGuildAdmin($member))
            <tr>
                <td>{{ $member->username }}</td>
                <td>Member</td>
                @if (Sentry::hasAccess('admin') || Sentry::hasAccess('Guild_'.$guild->tag.'_Admins'))
                <td>
                    <a href="{{ URL::to('/guilds/'.$guild->id.'/promote/'.$member->id) }}">Promote</a>
                    <a href="{{ URL::to('/guilds/'.$guild->id.'/kick/'.$member->id) }}">Kick</a>
                </td>
                @endif
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

@if (Sentry::hasAccess('admin') || Sentry::hasAccess('Guild_'.$guild->tag.'_Admins'))
<div class="panel panel-default">
    <div class="panel-heading">Add member</div>
    <div class="panel-body">
        {{ Form::open(array('route' => 'guildAddMember')) }}
        <div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}">
            {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) }}
            {{ ($errors->has('username') ? $errors->first('username') : '') }}
        </div>
        {{ Form::hidden('guild_id', $guild->id) }}
        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
    </div>
</div>
@endif
@stop

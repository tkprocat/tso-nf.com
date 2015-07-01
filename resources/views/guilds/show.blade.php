@extends('layouts.default')

{{-- Content --}}
@section('content')
<h4>{{ $guild->name }}</h4>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Username</th>
        <th>Rank</th>
        @if (Entrust::hasRole('admin') || ($guild_admin))
        <th></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($admins as $admin)
    <tr>
        <td>{{ $admin->username }}</td>
        <td>Admin</td>
        @if (Entrust::hasRole('admin') || ($guild_admin))
        <td><a href="{{ URL::to('/guilds/'.$guild->id.'/demote/'.$admin->id) }}">Demote</a></td>
        @endif
    </tr>
    @endforeach

    @foreach($members as $member)
        @if (!$guild->isGuildAdmin($member))
            <tr>
                <td>{{ $member->username }}</td>
                <td>Member</td>
                @if (Entrust::hasRole('admin') || ($guild_admin))
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

@if ($isGuildAdmin === true)
<div class="panel panel-default">
    <div class="panel-heading">Add member</div>
    <div class="panel-body">
        <form method="POST" action="https://tso-nf.com/guilds/addMember" accept-charset="UTF-8" class="form-inline">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}">
                <input type="text" class="form-control" placeholder="Username">
                {{ ($errors->has('username') ? $errors->first('username') : '') }}
                <input type="hidden" name="guild_id" value="{{ $guild->id }}">
                <input type="submit" value="Add" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endif
@stop

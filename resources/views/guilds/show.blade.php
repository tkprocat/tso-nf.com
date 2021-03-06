@extends('layouts.default')

{{-- Content --}}
@section('content')
<h4>{{ $guild->name }}</h4>

@include('errors.list')

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Username</th>
        <th>Rank</th>
        @if (Entrust::hasRole('admin') || (Entrust::can('admin-guild')))
        <th></th>
        @endif
    </tr>
    </thead>
    <tbody>

    @foreach($admins as $admin)
    <tr>
        <td>{{ $admin->username }}</td>
        <td>Admin</td>
        @if (Entrust::hasRole('admin') || (Entrust::can('admin-guild')))
        <td><a href="{{ URL::to('/guilds/'.$guild->id.'/demote/'.$admin->user_id) }}">Demote</a></td>
        @endif
    </tr>
    @endforeach

    @foreach($members as $member)
        @if (!$guild->isGuildAdmin($member))
            <tr>
                <td>{{ $member->username }}</td>
                <td>Member</td>
                @if (Entrust::hasRole('admin') || (Entrust::can('admin-guild')))
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
@stop

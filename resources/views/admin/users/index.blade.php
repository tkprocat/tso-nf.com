@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Users'))
<div class="panel panel-default">
    <div class="panel-heading">Users;</div>
    <div class="panel-body">
        <table class="table table-striped table-hover sortable" id="users">
            <thead>
            <tr>
                <th>Username</th>
                <th>Guild</th>
                <th>Loot added</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
            <tr>
                <td><a href="{{ URL::to('/admin/users/') .'/'. $user->id }}">{{ $user->username }}</a></td>
                <td>@if ($user->guild != null) {{ $user->guild->name }} @endif</td>
                <td>{{ $user->userAdventure()->count() }}</td>
                @if ($user->activated == 1)
                <td>Active</td>
                @else
                <td>Inactive</td>
                @endif
                <td><a href="/admin/users/{{ $user->id }}/edit">Edit</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
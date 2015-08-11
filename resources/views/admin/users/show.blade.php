@extends('layouts.default-admin')
@include('admin.menu', array('active' => 'Users'))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Account Profile</div>
    <div class="panel-body">
        <p><strong>Username:</strong> {{ $user->username }} </p>
        <p><strong>Guild:</strong> {{ $user->guild_id > 0 ? $user->guild->name : 'N/A' }} </p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Activated: </strong>{{ $user->activated ? 'Yes' : 'No' }}</p>
        <p><strong>Price admin: </strong>{{ $user->hasRole('prices_admin') ? 'Yes' : 'No' }}</p>
        <p><strong>Account created: </strong> {{ $user->created_at }}</p>
        <p><strong>Last updated: </strong>{{ $user->updated_at }}</p>
        <p><strong>Last login: </strong>{{ $user->last_login }}</p>
    </div>
</div>
@stop

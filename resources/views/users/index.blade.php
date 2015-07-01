@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<h4>Users:</h4>
@include('errors.list')
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="users">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Guild</th>
                        <th>Loot added</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><a href="{{ URL::to('stats/personal') .'/'. $user->username }}">{{ $user->username }}</a></td>
                        <td>@if (isset($user->guild->name)) {{ $user->guild->name }} @endif</td>
                        <td>{{ $user->userAdventure()->count() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $users->render() !!}
        </div>
    </div>
</div>
@stop
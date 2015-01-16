@extends('layouts.default-admin')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li><a href="/admin/">Overview</a></li>
                <li class="active"><a href="/admin/users">Users <span class="sr-only">(current)</span></a></li>
                <li><a href="/admin/adventures">Adventures</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h4>Current Users:</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover sortable" id="users">
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>Guild</th>
                                <th>Loot added</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td><a href="{{ URL::to('/users/') .'/'. $user->id }}">{{ $user->username }}</a></td>
                                <td>@if ($user->guild() != null) {{ $user->guild()->name }} @endif</td>
                                <td>{{ $user->userAdventure()->count() }}</td>
                                @if ($user->activated == 1)
                                <td>Active</td>
                                @else
                                <td>Inactive</td>
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
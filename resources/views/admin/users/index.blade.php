@extends('layouts.default-admin')

{{-- Web site Title --}}
@section('title')
@parent
Home
@stop

{{-- Content --}}
@section('content')
@include('admin.menu', array('active' => 'Users'))
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Current Users:</h1>
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
                        <td><a href="{{ URL::to('/admin/users/') .'/'. $user->id }}/edit">{{ $user->username }}</a></td>
                        <td>@if ($user->guild != null) {{ $user->guild->name }} @endif</td>
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
@stop
@extends('layouts.default-admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="/admin/">Overview <span class="sr-only">(current)</span></a></li>
                <li><a href="/admin/users">Users</a></li>
                <li><a href="/admin/adventures">Adventures</a></li>
                <li><a href="/admin/adventures/create">- Add new Adventure</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <td>Active users:</td><td>{{ $userCount }}</td>
                    </tr>
                    <tr>
                        <td>Registered adventures:</td><td>{{ $registeredAdventures }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>
                <div class="panel-body">
                    @include('errors.list')
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
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready( function () {
        $('#users').DataTable({
            paging: false
        });
    });
</script>
@stop
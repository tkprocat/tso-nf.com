@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Adventures'))
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<div class="panel panel-info">
    <div class="panel-heading">Adventures</div>
    <div class="panel-body">
        @include('layouts/notifications')
        <table class="table table-striped table-hover sortable" id="adventures">
            <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Disabled</th>
            </tr>
            </thead>
            <tbody>
            @foreach($adventures as $adventure)
                <tr>
                    <td><a href="{{ url("/admin/adventures/$adventure->id") }}">{{ $adventure->name }}</a></td>
                    <td>{{ $adventure->type }}</td>
                    <td>
                        @if($adventure->disabled === 1)
                        Yes
                        @else
                        No
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready( function () {
        $('#adventures').DataTable({
            paging: false
        });
    });
</script>
@stop
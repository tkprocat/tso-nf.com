@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<table class="table table-striped table-bordered" id="adventures">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Type</th>
        <th>Slots</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    <tbody>
    @foreach($adventures as $adventure)
        <tr>
            <td><a href="/stats/global/{{ urlencode($adventure->name) }}">{{ $adventure->name }}</a></td>
            <td>{{ $adventure->type }}</td>
            <td>{{ $adventure->slotCount }}</td>
            <td>{{ $adventure->played->count() }}</td>
            <td>{{ number_format($adventure->played->count() / $total_played * 100) }}%</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th style="text-align: center" colspan="5">Total adventures registered: {{ $total_played }}</th>
    </tr>
    </tfoot>
</table>
<script>
    $(document).ready(function(){
        $('#adventures').DataTable({
            paging: false
        });
    });
</script>
@stop

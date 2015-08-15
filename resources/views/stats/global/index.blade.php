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
        <th>Estimated value (GC)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($adventures as $adventure)
        <tr>
            <td><a href="/stats/global/{{ urlencode($adventure->name) }}">{{ $adventure->name }}</a></td>
            <td>{{ $adventure->type }}</td>
            <td style="text-align: right">{{ $adventure->slotCount }}</td>
            <td style="text-align: right">{{ $adventure->played->count() }}</td>
            <td style="text-align: right">{{ number_format($adventure->played->count() / $total_played * 100) }}%</td>
            <td style="text-align: right">{{ number_format($adventure->estimated_value) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th style="text-align: center" colspan="6">Total adventures registered: {{ $total_played }}</th>
    </tr>
    </tfoot>
</table>
<br>
<div class="panel panel-info">
    <div class="panel-heading">Help</div>
    <div class="panel-body">
        <p>Our estimated value for an adventure is based on all registered loot from players for said adventure.
        The numbers are updated once every day to account for new loot registered and any price changes to items used previously.</p>
        <p>This update runs at midnight CET.</p>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#adventures').DataTable({
            paging: false
        });
    });
</script>
@stop

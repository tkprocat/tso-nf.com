@extends('layouts.default')
@section('content')
<br>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Type</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    @foreach($adventures as $adventure)
        <tr>
            <td><a href="/stats/guild/adventure/{{ urlencode($adventure->name) }}">{{ $adventure->name }}</a></td>
            <td>{{ $adventure->type }}</td>
            <td>{{ $adventure->played }}</td>
            <td>{{ number_format($adventure->played / $total_played * 100) }}%</td>
        </tr>
    @endforeach
</table>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="text-align: center">Total adventures registered: {{ $total_played }}</th>
    </tr>
    </thead>
</table>
@stop

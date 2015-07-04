@extends('layouts.default')
@section('content')
<br>
<table style="float: left; width: 48%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    {{--*/ $no = 0 /*--}}
    @foreach($adventures->slice(0, count($adventures)/2+1) as $adventure)
        <tr>
            <td><a href="/stats/global/{{ urlencode($adventure->name) }}">{{ $adventure->type . ' - ' . $adventure->name }}</a></td>
            <td>{{ $adventure->played->count() }}</td>
            <td>{{ number_format($adventure->played->count() / $total_played * 100) }}%</td>
        </tr>

        @define($no++);
    @endforeach
</table>

<table style="float: right; width: 48%" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Adventure</th>
        <th>Played</th>
        <th>Played %</th>
    </tr>
    </thead>
    @foreach($adventures->slice($no, count($adventures)/2) as $adventure)
        <tr>
            <td><a href="/stats/global/{{ urlencode($adventure->name) }}">{{ $adventure->type . ' - ' . $adventure->name }}</a></td>
            <td>{{ $adventure->played->count() }}</td>
            <td>{{ number_format($adventure->played->count() / $total_played * 100) }}%</td>
        </tr>

        @define($no++);
    @endforeach
    @if ($no % 2 != 0)
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
    @endif
</table>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="text-align: center">Total adventures registered: {{ $total_played }}</th>
    </tr>
    </thead>
</table>
@stop

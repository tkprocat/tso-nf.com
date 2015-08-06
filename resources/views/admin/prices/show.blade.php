@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Prices'))
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="panel panel-default">
        <div class="panel-heading">Price details for {{ $item->name }}</div>
        <div class="panel-body">
            @include('layouts/notifications')
            <table class="table table-striped table-hover sortable" id="adventures">
                <thead>
                <tr>
                    <th>Min.</th>
                    <th>Avg.</th>
                    <th>Max.</th>
                    <th>Changed at</th>
                    <th>Changed by</th>
                </tr>
                </thead>
                <tbody>
                @foreach($price_history as $historyItem)
                    <tr>
                        <td>{{ $historyItem->min_price }}</td>
                        <td>{{ $historyItem->avg_price }}</td>
                        <td>{{ $historyItem->max_price }}</td>
                        <td>{{ $historyItem->created_at }}</td>
                        <td>{{ $historyItem->user->username }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
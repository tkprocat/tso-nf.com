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
                    <li><a href="/admin/users">Users</a></li>
                    <li><a href="/admin/adventures">Adventures</a></li>
                    <li><a href="/admin/adventures/create">- Add new adventure</a></li>
                    <li class="active"><a href="/admin/prices">Prices <span class="sr-only">(current)</span></a></li>
                    <li><a href="/admin/prices/create">- Add new item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h4>Price list item details:</h4>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ $item->name }}</div>
                            <div class="panel-body">
                                <table class="table table-striped table-hover sortable" id="adventures">
                                    <thead>
                                    <tr>
                                        <th>Slot</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($price_history as $historyItem)
                                        <tr>
                                            <td>{{ $historyItem->min_price }}</td>
                                            <td>{{ $historyItem->avg_price }}</td>
                                            <td>{{ $historyItem->max_price }}</td>
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
    </div>
@stop
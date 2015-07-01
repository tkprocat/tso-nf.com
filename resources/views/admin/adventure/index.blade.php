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
                    <li class="active"><a href="/admin/adventures">Adventures <span class="sr-only">(current)</span></a></li>
                    <li><a href="/admin/adventures/create">- Add new adventure</a></li>
                    <li><a href="/admin/prices">Prices</a></li>
                    <li><a href="/admin/prices/create">- Add new item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                @include('layouts/notifications')
                <h4>Adventures:</h4>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
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
                </div>
            </div>
        </div>
    </div>
@stop
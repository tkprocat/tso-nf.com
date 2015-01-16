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
                    <li class="active"><a href="/admin/adventures">Adventures <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h4>Adventures:</h4>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ $adventure->name }}</div>
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
                                    @foreach($adventure->loot as $loot)
                                        <tr>
                                            <td>{{ $loot->slot }}</td>
                                            <td>{{ $loot->type }}</td>
                                            <td>{{ $loot->amount }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <a href="{{ URL::to("/admin/adventures") }}" class="btn btn-primary">Back</a>
                        <a href="{{ URL::to("/admin/adventures/$adventure->id/edit") }}"
                           class="btn btn-warning">Update</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
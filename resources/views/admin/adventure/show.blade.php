@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Adventures'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Adventure: {{ $adventure->name }}</div>
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
                                            <td>{{ $loot->name }}</td>
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
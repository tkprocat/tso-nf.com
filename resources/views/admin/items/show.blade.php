@extends('layouts.default-admin')
@section('content')
    @include('admin.menu', array('active' => 'Adventures'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Item: {{ $item->name }}</div>
                            <div class="panel-body">
                                <ul>
                                    <li>Category: {{ $item->category }}</li>
                                    <li>Created by: {{ $item->created_by }}</li>
                                    <li>Created at: {{ $item->created_at }}</li>
                                    <li>Last updated at: {{ $item->updated_at }}</li>
                                </ul>
                            </div>
                        </div>
                        <a href="{{ URL::to("/admin/items") }}" class="btn btn-primary">Back</a>
                        <a href="{{ URL::to("/admin/items/$item->id/edit") }}"
                           class="btn btn-warning">Update</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
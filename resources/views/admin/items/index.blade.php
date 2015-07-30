@extends('layouts.default-admin')

@section('content')
@include('admin.menu', array('active' => 'Items'))
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Items</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover sortable" id="adventures">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><a href="{{ url("/admin/items/$item->id") }}">{{ $item->name }}</a></td>
                            <td>{{ $item->category }}</td>
                            <td><a href="{{ url("/admin/items/$item->id/edit") }}" class="btn btn-warning btn-xs">Update item</a>
                            <a href="/admin/items/{{ $item->id }}" data-method="delete" rel="nofollow"
                               class="btn btn-danger btn-xs" data-confirm="Are you sure you want to delete this loot entry?">
                                Delete
                            </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
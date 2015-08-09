@extends('layouts.default-admin')

@section('content')
@include('admin.menu', array('active' => 'Items'))
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="panel panel-default">
        <div class="panel-heading">Items</div>
        <div class="panel-body">
            @include('errors.list')

            <table class="table table-striped table-hover sortable" id="items">
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
                        <td>
                            <a href="{{ url("/admin/items/$item->id/edit") }}" class="btn btn-warning btn-xs">Update item</a>
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
<script>
    $(document).ready( function () {
        $('#items').DataTable({
                paging: false
        });
    });
</script>
@stop
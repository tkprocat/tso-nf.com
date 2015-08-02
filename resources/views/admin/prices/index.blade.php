@extends('layouts.default-admin')

@section('content')
@include('admin.menu', array('active' => 'Prices'))
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <div class="panel panel-default">
        <div class="panel-heading">Prices</div>
        <div class="panel-body">
            @include('errors.list')
            <div class="table-responsive">
                <table class="table table-striped table-hover sortable" id="prices">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Min. price</th>
                        <th>Avg. price</th>
                        <th>Max. price</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><a href="{{ url("/admin/prices/$item->id") }}">{{ $item->name }}</a></td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->currentPrice->min_price }}</td>
                            <td>{{ $item->currentPrice->avg_price }}</td>
                            <td>{{ $item->currentPrice->max_price }}</td>
                            <td><a href="{{ url("/admin/prices/$item->id/edit") }}" class="btn btn-warning btn-xs">Change price</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready( function () {
        $('#prices').DataTable({
            paging: false
        });
    });
</script>
@stop
@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Price list</div>
                <div class="panel-body">
                    <p>
                        Prices on this list was originally based off the <a href="http://tiny.cc/newfoundlandtrade">Newfoundland Trade Sheet by THU & TP1</a>, all credits to them for providing the information.
                    </p>
                    <table class="table table-striped"id="items">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Min.</th>
                                <th>Avg.</th>
                                <th>Max.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->currentPrice->min_price }}</td>
                                    <td>{{ $item->currentPrice->avg_price }}</td>
                                    <td>{{ $item->currentPrice->max_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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

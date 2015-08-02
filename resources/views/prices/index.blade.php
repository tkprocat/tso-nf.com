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
                    <div class="form-inline">
                        Show prices for quantities of:
                        <select id="quantity" class="form-control">
                            <option value="1">1</option>
                            <option value="25">25</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                    <table class="table table-striped"id="items">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
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
                                    <td>1</td>
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
    $('#quantity').change(function(){
        var itemsTable = $('#items');
        var quantity = $('#quantity').val();
        //Delete old data
        itemsTable.empty();
        itemsTable.DataTable().destroy();
        //Load data via ajax
        $.get("/prices/getItemsWithPrices", function(data) {
            //Repopulate table
            $.each(data, function(index, item) {
                if (item.category != 'Decoration') {
                    itemsTable.append(
                            '<tr><td>'+item.name+'</td>'+
                                '<td>'+item.category+'</td>' +
                                '<td>'+quantity+'</td>' +
                                '<td>'+Math.round(item.current_price.min_price*quantity*1000)/1000+'</td>' +
                                '<td>'+Math.round(item.current_price.avg_price*quantity*1000)/1000+'</td>' +
                                '<td>'+Math.round(item.current_price.max_price*quantity*1000)/1000+'</td></tr>'
                    );
                } else {
                    itemsTable.append(
                            '<tr><td>'+item.name+'</td>'+
                                    '<td>'+item.category+'</td>' +
                                    '<td>1</td>' +
                                    '<td>'+Math.round(item.current_price.min_price*1000)/1000+'</td>' +
                                    '<td>'+Math.round(item.current_price.avg_price*1000)/1000+'</td>' +
                                    '<td>'+Math.round(item.current_price.max_price*1000)/1000+'</td></tr>'
                    );
                }
            });
            $('#items').DataTable({
                paging: false
            });
        });
    });
</script>
@stop

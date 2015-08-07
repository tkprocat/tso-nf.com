@extends('layouts.default')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
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
                            <option value="0">Default</option>
                            <option value="1">1</option>
                            <option value="25">25</option>
                            <option value="1000">1000</option>
                        </select>
                        <p>Please use 1 for decorations, adventures, loot spots, 25 for stackable items like buffs, and 1000 for most resources.</p>
                    </div>
                    <table class="table table-striped" id="items">
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
                            <tr><td>Loading data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/requirejs/require.js"></script>
<script>
    var items;
    var formatter;

    require.config({
        paths: {
            // Globalize dependencies paths.
            cldr: "/js/cldr",

            // Unicode CLDR JSON data.
            "cldr-data": "/js/cldr-data",

            // require.js plugin we'll use to fetch CLDR JSON content.
            json: "/js/requirejs/json",

            // text is json's dependency.
            text: "/js/requirejs/text",

            // Globalize.
            globalize: "/js/globalize"
        }
    });

    require([
        "globalize",

        // CLDR content.
        "json!cldr-data/main/en/numbers.json",
        "json!cldr-data/supplemental/likelySubtags.json",

        // Extend Globalize with Date and Number modules.
        "globalize/number"
    ], function( Globalize, enNumbers, likelySubtags) {
        Globalize.load(enNumbers);
        Globalize.load(likelySubtags);
        Globalize.locale( "en" );
        formatter = Globalize.numberFormatter({
            minimumSignificantDigits: 1,
            maximumSignificantDigits: 3
        });

        $(document).ready( function () {
            $.get("/prices/getItemsWithPrices", function(data) {
                items = data;
                $('#quantity').change();
            });
        });
    });


    function formatNumber(value) {
        //Hack since numberFormatter returns NaN on zero.
        value = parseInt(value);

        if (value == 0)
            return 0;
        else
            return formatter(value);
    }

    $('#quantity').change(function(){
        var itemsTable = $('#items tbody');
        var quantity = $('#quantity').val();
        //Delete old data
        if ($.fn.dataTable.isDataTable('#items')) {
            $('#items').DataTable().destroy();
        }
        itemsTable.empty();
        //Repopulate table
        $.each(items, function(index, item) {
            if (quantity == 0) {
                if ((item.category == 'Decoration') || (item.category == 'Unknown')) {
                    itemsTable.append(
                            '<tr><td><a href="/prices/'+encodeURIComponent(item.name)+'">'+item.name+'</a></td>'+
                            '<td>'+item.category+'</td>' +
                            '<td>1</td>' +
                            '<td>'+formatNumber(item.current_price.min_price)+'</td>' +
                            '<td>'+formatNumber(item.current_price.avg_price)+'</td>' +
                            '<td>'+formatNumber(item.current_price.max_price)+'</td></tr>'
                    )
                } else if (item.category == 'Buff') {
                    itemsTable.append(
                            '<tr><td><a href="/prices/'+encodeURIComponent(item.name)+'">'+item.name+'</a></td>'+
                            '<td>'+item.category+'</td>' +
                            '<td>25</td>' +
                            '<td>'+formatNumber(item.current_price.min_price*25)+'</td>' +
                            '<td>'+formatNumber(item.current_price.avg_price*25)+'</td>' +
                            '<td>'+formatNumber(item.current_price.max_price*25)+'</td></tr>'
                    )
                } else {
                    itemsTable.append(
                            '<tr><td><a href="/prices/'+encodeURIComponent(item.name)+'">'+item.name+'</a></td>'+
                            '<td>'+item.category+'</td>' +
                            '<td>'+formatNumber(1000)+'</td>' +
                            '<td>'+formatNumber(item.current_price.min_price*1000)+'</td>' +
                            '<td>'+formatNumber(item.current_price.avg_price*1000)+'</td>' +
                            '<td>'+formatNumber(item.current_price.max_price*1000)+'</td></tr>'
                    )
                }
            } else {
                itemsTable.append(
                    '<tr><td><a href="/prices/'+encodeURIComponent(item.name)+'">'+item.name+'</a></td>'+
                    '<td>'+item.category+'</td>' +
                    '<td>'+formatNumber(quantity)+'</td>' +
                    '<td>'+formatNumber(item.current_price.min_price*quantity)+'</td>' +
                    '<td>'+formatNumber(item.current_price.avg_price*quantity)+'</td>' +
                    '<td>'+formatNumber(item.current_price.max_price*quantity)+'</td></tr>'
                )
            }
        });

        $('#items').DataTable({
            paging: false
        });
    });
</script>
@stop

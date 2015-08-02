@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Simple Calc</div>
                <div class="panel-body">
                    <form class="form-inline">
                        Trade:
                        <input type="text" id="tradeQuantity" value="1" class="form-control">
                        <select id="tradeItemFrom" class="form-control">
                            <option>Loading data..</option>
                        </select>
                        for
                        <select id="tradeItemTo" class="form-control">
                            <option>Loading data..</option>
                        </select>
                        <button id="calc" class="btn btn-success">Calc</button>
                    </form>
                    <br>
                    <table class="table table-bordered table-striped table-hover" id="result"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var items = null;
    var tradeItemFromControl = $('#tradeItemFrom');
    var tradeItemToControl = $('#tradeItemTo');
    var resultTable = $('#result');
    $(document).ready(function() {
        $.get("/prices/getItemsWithPrices", function(data) {
            items = data;
            tradeItemFromControl.empty();
            tradeItemToControl.empty();
            $.each(data, function(index, item) {
                tradeItemFromControl.append(new Option(item.name, item.id));
                tradeItemToControl.append(
                        $('<option>', { value : item.id, text: item.name })
                );
            });
            $('#tradeItemTo').val($('#tradeItemTo option:contains(Gold Coins)').val());
        });
    });

    function findItemPrice(itemId) {
        return $.grep(items, function(item) {
            return item.id == itemId;
        });
    };

    $('#calc').click(function() {
        //Get price information
        var itemFrom = findItemPrice(tradeItemFromControl.val())[0];
        var itemTo = findItemPrice(tradeItemToControl.val())[0];
        var amount = $('#tradeQuantity').val();
        //Make sure the result table is empty.
        resultTable.empty();

        //Add header information.
        resultTable.append('<tr><td rowspan="2" style="vertical-align: middle">' + itemFrom.name + '</td><td colspan="4" style="text-align: center">' + itemTo.name + '</td></tr>');
        resultTable.append('<tr><td>Min:</td><td>Avg:</td><td>Max:</td></tr>');
        addResultData(amount, itemFrom, itemTo, false);
        addResultData(500, itemFrom, itemTo, true);
        addResultData(1000, itemFrom, itemTo, true);
        addResultData(2500, itemFrom, itemTo, true);
        addResultData(5000, itemFrom, itemTo, true);
        resultTable.append('<tr id="extratoggle"><td colspan="4" style="text-align: center">Click here to see results for commonly traded quantities.</td></tr>');
        $('.extra').hide();
        $('#extratoggle').click(function() {
            $('.extra').show();
            $(this).hide();
        });
        return false;
    });

    function addResultData(amount, itemFrom, itemTo, extra) {
        var minPrice = Math.max(Math.round((amount * itemFrom.current_price.min_price) / (itemTo.current_price.max_price) * 100) / 100, 0);
        var avgPrice = Math.max(Math.round((amount * itemFrom.current_price.avg_price) / (itemTo.current_price.avg_price) * 100) / 100, 0);
        var maxPrice = Math.max(Math.round((amount * itemFrom.current_price.max_price) / (itemTo.current_price.min_price) * 100) / 100, 0);

        if (extra)
            resultTable.append('<tr class="extra"><td>'+amount+'</td><td>' + minPrice + '</td><td>' + avgPrice + '</td><td>' + maxPrice + '</td></tr>');
        else
            resultTable.append('<tr><td>'+amount+'</td><td>' + minPrice + '</td><td>' + avgPrice + '</td><td>' + maxPrice + '</td></tr>');
    }
</script>
@stop
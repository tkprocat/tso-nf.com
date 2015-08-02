@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Advanced Calc</div>
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="row">
                            <div class="col-md-1 col-md-offset-1">
                                <label for="tradeItemFrom">From:</label>
                            </div>
                            <div class="col-md-4">
                                <select id="tradeItemFrom" class="form-control"></select>
                            </div>
                            <div class="col-md-1">
                                <label for="tradeItemTo">To:</label>
                            </div>
                            <div class="col-md-4">
                                <select id="tradeItemTo" class="form-control"></select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-1 col-md-offset-1">
                                <label for="tradePriceFrom">Price:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="tradePriceFrom" class="form-control">
                                <select id="tradePriceFromType" class="form-control">
                                    <option>min</option><option selected="selected">avg</option><option>max</option><option>guild</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label for="tradePriceTo">Price:</label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="tradePriceTo" class="form-control">
                                <select id="tradePriceToType" class="form-control">
                                    <option>min</option><option selected="selected">avg</option><option>max</option><option>guild</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-1 col-md-offset-1">
                                <label for="tradeQuantityFrom">Amount:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="tradeQuantityFrom" value="1" class="form-control">
                            </div>
                            <div class="col-md-1 col-md-offset-1">
                                <label for="tradeQuantityTo">Amount:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="tradeQuantityTo" value="0" class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-1 col-md-offset-1">
                                <strong>Value:</strong>
                            </div>
                            <div class="col-md-3">
                                <div id="tradeValueFrom">
                                    <strong>0</strong>
                                </div>
                            </div>
                            <div class="col-md-1 col-md-offset-1">
                                <strong>Value:</strong>
                            </div>
                            <div class="col-md-3">
                                <div id="tradeValueTo">
                                    <strong>0</strong>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div style="text-align: center">
                                <input id="calc" type="button" value="Calc" class="btn btn-success">
                            </div>
                        </div>
                        <br>
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
            $('#tradeItemFrom').change();
            $('#tradeItemTo').change();
        });
    });

    function findItemPrice(itemId) {
        return $.grep(items, function(item) {
            return item.id == itemId;
        });
    };

    $('#tradeItemFrom,#tradePriceFromType').change(function() {
        var item = findItemPrice($('#tradeItemFrom option:selected').val())[0];
        switch($('#tradePriceFromType option:selected').text()) {
            case 'min':
                var price = item.current_price.min_price;
                break;
            case 'avg':
                var price = item.current_price.avg_price;
                break;
            case 'max':
                var price = item.current_price.max_price;
                break;
        }
        $('#tradePriceFrom').val(price);
    });

    $('#tradeItemTo,#tradePriceToType').change(function() {
        var item = findItemPrice($('#tradeItemTo option:selected').val())[0];
        switch($('#tradePriceToType option:selected').text()) {
            case 'min':
                var price = item.current_price.min_price;
                break;
            case 'avg':
                var price = item.current_price.avg_price;
                break;
            case 'max':
                var price = item.current_price.max_price;
                break;
        }
        $('#tradePriceTo').val(price);
    });

    $('#calc').click(function() {
        //Get price information
        var tradeValueFrom = $('#tradeValueFrom').val();
        var tradeValueTo = $('#tradeValueFrom').val();

        var tradeFromPrice = $('#tradePriceFrom').val();
        var tradeFromValue = tradeFromPrice * $('#tradeQuantityFrom').val();
        $('#tradeValueFrom').text((tradeFromValue * 10) / 10);
        $('#tradeValueTo').text((tradeFromValue * 10) / 10);
        var tradeToPrice = $('#tradePriceTo').val();
        var tradeToAmount = tradeFromValue / tradeToPrice;
        $('#tradeQuantityTo').val(Math.round(tradeToAmount * 100) / 100);

        return false;
    });
</script>
@stop
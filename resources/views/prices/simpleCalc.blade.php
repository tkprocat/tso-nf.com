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
<script src="/js/requirejs/require.js"></script>
<script>
    var items = null;
    var tradeItemFromControl = $('#tradeItemFrom');
    var tradeItemToControl = $('#tradeItemTo');
    var resultTable = $('#result');
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


    /**
     * 2. Require dependencies and run your code.
     */
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
        resultTable.append('<tr id="extratoggle"><td colspan="4" style="text-align: center"><a href="#">Click here to see results for commonly traded quantities.</a></td></tr>');
        $('.extra').hide();
        $('#extratoggle').click(function() {
            if ($('.extra').is(':visible')) {
                $('.extra').hide();
                $(this).html('<td colspan="4" style="text-align: center"><a href="#">Click here to see results for commonly traded quantities.</a></td>');
            } else {
                $('.extra').show();
                $(this).html('<td colspan="4" style="text-align: center"><a href="#">Click here to hide results for commonly traded quantities.</a></td>');
            }
        });
        return false;
    });

    function formatNumber(value) {
        if (isNaN(value))
            return 0;

        value = parseInt(value);
        value = formatter(value);

        //Hack since numberFormatter returns NaN on zero.
        if (value == 'NaN')
            return 0;
        else
            return value;
    }

    function addResultData(amount, itemFrom, itemTo, extra) {
        var minPrice = (amount * itemFrom.current_price.min_price) / (itemTo.current_price.max_price);
        var avgPrice = (amount * itemFrom.current_price.avg_price) / (itemTo.current_price.avg_price);
        var maxPrice = (amount * itemFrom.current_price.max_price) / (itemTo.current_price.min_price);

        if (extra)
            resultTable.append('<tr class="extra">'+
                    '<td>' + formatNumber(amount) + '</td>'+
                    '<td>' + formatNumber(minPrice) + '</td>'+
                    '<td>' + formatNumber(avgPrice) + '</td>'+
                    '<td>' + formatNumber(maxPrice) + '</td>'+'' +
                    '</tr>');
        else
            resultTable.append('<tr>'+
                    '<td>' + formatNumber(amount) + '</td>'+
                    '<td>' + formatNumber(minPrice) + '</td>'+
                    '<td>' + formatNumber(avgPrice) + '</td>'+
                    '<td>' + formatNumber(maxPrice) + '</td>'+
                    '</tr>');
    }
</script>
@stop
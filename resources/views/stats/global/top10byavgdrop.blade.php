@extends('layouts.default')

{{-- Content --}}
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        Top 10 adventures for item by amount.
    </div>
    <div class="panel-body" style="margin-left: 20px">
        <form class="form-inline" role="form">
            <div class="form-group ">
                <label for="loottype_avgdrop">Item:</label>
                <select name="loottype_avgdrop" style="width: 200px" class="form-control">
                    <option value="-1">Please selection item.</option>
                    @foreach($loot_types as $id => $value)
                        <option value="{{ $id }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>
<table id="result_avgdrop" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Adventure</th>
            <th>Total played</th>
            <th>Total dropped</th>
            <th>Average drop</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

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
    });


    function formatNumber(value) {
        //Hack since numberFormatter returns NaN on zero.
        value = parseInt(value);

        if (value == 0)
            return 0;
        else
            return formatter(value);
    }

    $('[name="loottype_avgdrop"]').change(function () {
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/global/getTop10BestAdventuresForLootTypeByAvgDrop') }}/"+$('[name="loottype_avgdrop"] option:selected').text(),
            statusCode: {
                401: function () {
                    self.location = "/login";
                    return false;
                }
            },
            success: function (lootdata) {
                $('#result_avgdrop tbody').empty();

                $(lootdata).each(function () {
                    $('#result_avgdrop tbody').append('<tr>'+
                            '<td>'+this.name+'</td>'+
                            '<td>'+formatNumber(this.totalPlayed)+'</td>'+
                            '<td>'+formatNumber(this.totalDropped)+'</td>'+
                            '<td>'+formatNumber(this.avgDrop)+'</td>'+
                            '</tr>');
                });
            },
            error: function (msg) {
            }
        });
    });
</script>
@stop
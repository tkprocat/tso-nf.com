@extends('layouts.default')

{{-- Content --}}
@section('content')
<div class="alert alert-warning">This page is part of our Labs section, please consider the information below broken / wrong while we're in testing.<br>
    As always, please report any feedback to Procat ingame.
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Top 10 adventures for loot by amount.
    </div>
    <span style="padding: 10px">Select loot: {{ Form::select('loottype_avgdrop', $lootTypes, array('','')) }}</span>
    <table id="result_avgdrop" class="table">
        <thead>
        <tr><th>Adventure</th><th>Average drop</th></tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Top 10 adventures for loot by dropchance.
    </div>
    <span style="padding: 10px">Select loot: {{ Form::select('loottype_dropchance', $lootTypes, array('','')) }}</span>
    <table id="result_dropchance" class="table">
        <thead>
        <tr><th>Adventure</th><th>Drop chance</th><th>Times played</th></tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<script>
    $('[name="loottype_avgdrop"]').change(function () {
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/labs/getTop10BestAdventuresForLootTypeByAvgDrop') }}",
            data: {
                type: $('[name="loottype_avgdrop"] option:selected').text()
            },
            statusCode: {
                401: function () {
                    self.location = "/login";
                    return false;
                }
            },
            success: function (lootdata) {
                $('#result_avgdrop tbody').empty();

                $(lootdata).each(function () {
                    $('#result_avgdrop tbody').append('<tr><td>'+this.Name+'</td><td>'+this.AvgDrop+'</td></tr>');
                });
            },
            error: function (msg) {
            }
        });
    });

    $('[name="loottype_dropchance"]').change(function () {
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/labs/getTop10BestAdventuresForLootTypeByDropChance') }}",
            data: {
                type: $('[name="loottype_dropchance"] option:selected').text()
            },
            statusCode: {
                401: function () {
                    self.location = "/login";
                    return false;
                }
            },
            success: function (lootdata) {
                $('#result_dropchance tbody').empty();

                $(lootdata).each(function () {
                    $('#result_dropchance tbody').append('<tr><td>'+this.Name+'</td><td>'+(Math.round(this.DropChance * 100)/100)+'%</td><td>'+this.Played+'</td></tr>');
                });
            },
            error: function (msg) {
            }
        });
    });
</script>
@stop
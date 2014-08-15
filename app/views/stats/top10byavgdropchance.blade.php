@extends('...layouts.default')

{{-- Content --}}
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        Top 10 adventures for loot by dropchance.
    </div>
    <div class="panel-body" style="margin-left: 20px">
        <form class="form-inline" role="form">
            <div class="form-group ">
                <label for="loottype_dropchance">Select loot:</label>
                {{ Form::select('loottype_dropchance', $loot_types, array('',''), array('style' => 'width: 200px','class' => 'form-control')) }}
            </div>
        </form>
    </div>
</div>
<table id="result_dropchance" class="table table-striped table-bordered">
    <thead>
    <tr><th>Adventure</th><th>Drop chance</th><th>Times played</th></tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>
    $('[name="loottype_dropchance"]').change(function () {
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/getTop10BestAdventuresForLootTypeByDropChance') }}",
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
                    $('#result_dropchance tbody').append('<tr><td>'+this.name+'</td><td>'+(Math.round(this.drop_chance * 100)/100)+'%</td><td>'+this.played+'</td></tr>');
                });
            },
            error: function (msg) {
            }
        });
    });
</script>
@stop
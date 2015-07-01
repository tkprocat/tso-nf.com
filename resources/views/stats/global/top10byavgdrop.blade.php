@extends('layouts.default')

{{-- Content --}}
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        Top 10 adventures for loot by amount.
    </div>
    <div class="panel-body" style="margin-left: 20px">
        <form class="form-inline" role="form">
            <div class="form-group ">
                <label for="loottype_dropchance">Select loot:</label>
                <select name="loottype_avgdrop" style="width: 200px" class="form-control">
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
    <tr><th>Adventure</th><th>Average drop</th></tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
    $('[name="loottype_avgdrop"]').change(function () {
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/getTop10BestAdventuresForLootTypeByAvgDrop') }}/"+$('[name="loottype_avgdrop"] option:selected').text(),
            statusCode: {
                401: function () {
                    self.location = "/login";
                    return false;
                }
            },
            success: function (lootdata) {
                $('#result_avgdrop tbody').empty();

                $(lootdata).each(function () {
                    $('#result_avgdrop tbody').append('<tr><td>'+this.name+'</td><td>'+this.avg_drop+'</td></tr>');
                });
            },
            error: function (msg) {
            }
        });
    });
</script>
@stop
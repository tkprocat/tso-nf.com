@extends('layouts.default')
@section('content')

<h1 style="text-align: center">Personal Stats</h1>

<div class="nav nav-pills nav-justified">
    <li>
        <a href="#general">Stats</a>
    </li>
    <li>
        <a href="#accumulated">Accumulated loot</a>
    </li>
    <li>
        <a href="/loot/{{$username}}">Latest loot</a>
    </li>
    <li>
        <a href="#adventuresplayed">Adventures played</a>
    </li>
</div>
<br>

<h2 id="general">Stats:</h2>
<table class="table table-striped table-bordered">
    <tbody>
        <tr>
            <td>Most played adventure:</td>
            <td>{{ $stats['most_played_adventure_name'] }}</td>
            <td>Least played adventure:</td>
            <td>{{ $stats['least_played_adventure_name'] }}</td>
        </tr>
        <tr>
            <td>Adventures registered this week:</td>
            <td>{{ $stats['adventures_played_this_week'] }}</td>
            <td>Adventures registered last week:</td>
            <td>{{ $stats['adventures_played_last_week'] }}</td>
        </tr>
    </tbody>
</table>
<br>

<h2 id="accumulated">Accumulated loot:</h2>
<div class="form-inline">
    Show loot submitted between
    <input type="text" class="form-control" value="2015-01-01" id="accumulatedloot-datefrom">
    and
    <input type="text" class="form-control" value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" id="accumulatedloot-dateto">
</div>
<div class="clearfix"></div>

<div id="accumulatedloot" style="margin-top: 5px">
    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...
</div>
<br>
<div class="clearfix"></div>

<h2 id="adventuresplayed">Adventures played:</h2>
<div class="form-inline">
    Show statistics for adventures registered between
    <input type="text" class="form-control" value="2015-01-01" id="adventuresplayed-datefrom">
    and
    <input type="text" class="form-control" value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" id="adventuresplayed-dateto">
</div>
<div id="adventuresplayedresult" style="margin-top: 5px">
    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...
</div>


<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Please confirm deletion of:</h4>
            </div>
            <div id="deleteModalBody" class="modal-body">

            </div>
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="deleteItemID" value="">

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteUserAdventure">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    //Code to handle deletion confirmation popup.
    $('.btn-deleteUserAdventure').click(function () {
        var loottext = $(this).parent().siblings(":eq(3)").text();
        var adventure = $(this).parent().siblings(":eq(2)").text();
        var date = $(this).parent().siblings(":eq(0)").text();
        var message = '<strong>' + adventure + ' registered at ' + date + ' </strong><br>Loot: ' + loottext;
        $('#deleteModalBody').html(message);
        $('#deleteItemID').val($(this).data('id'));
    });

    $('#deleteUserAdventure').click(function () {
        $.ajax({
            type: 'POST',
            url: './loot/delete/',
            data: {
                id: $('#deleteItemID').val(),
                _token: $('#_token').val()
            },
            statusCode: {
                401: function () {
                    self.location = "/login";
                    return false;
                }
            },
            success: function () {
                $('#latest_' + $('#deleteItemID').val()).remove();
                $('#deleteModal').modal('hide');
            },
            error: function (msg) {
                $('#modalError').html(msg.responseText);
            }
        });
    });

    $(document).ready(function () {
        $('#accumulatedloot-datefrom').datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(2015,0,1),
            maxDate: "D"
        }).change(function (e) {
            updateAccumulatedLoot();
        });

        $('#accumulatedloot-dateto').datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(2015,0,1),
            maxDate: "+1D"
        }).change(function (e) {
            updateAccumulatedLoot();
        });

        $('#adventuresplayed-datefrom').datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(2015,0,1),
            maxDate: "D"
        }).change(function (e) {
            updateAdventuresPlayed();
        });

        $('#adventuresplayed-dateto').datepicker({
            firstDay: 1,
            dateFormat: 'yy-mm-dd',
            minDate: new Date(2015,0,1),
            maxDate: "+1D"
        }).change(function (e) {
            updateAdventuresPlayed();
        });

        updateAccumulatedLoot();
        updateAdventuresPlayed();
    });

    function updateAccumulatedLoot() {
        var datefrom = $("#accumulatedloot-datefrom").val();
        var dateto = $("#accumulatedloot-dateto").val();
        //Change the old result to loading...
        $('#accumulatedloot').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...');
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/personal/accumulatedloot/'.$username) }}/" + datefrom + "/" + dateto,
            statusCode: {
                401: function () {
                    self.location = "/auth/login";
                    return false;
                }
            },
            success: function (msg) {
                $('#accumulatedloot').html(msg);
            },
            error: function (msg) {
            }
        });
    }

    function updateAdventuresPlayed() {
        var datefrom = $("#adventuresplayed-datefrom").val();
        var dateto = $("#adventuresplayed-dateto").val();
        $('#adventuresplayedresult').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...');
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/personal/adventuresplayed/'.$username) }}/" + datefrom + "/" + dateto,
            statusCode: {
                401: function () {
                    self.location = "/auth/login";
                    return false;
                }
            },
            success: function (msg) {
                $('#adventuresplayedresult').html(msg);
            },
            error: function (msg) {
            }
        });
    }
</script>
@stop
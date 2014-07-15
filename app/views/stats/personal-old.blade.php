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
        <a href="#latest">Latest loot</a>
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
        <td>{{ $stats['mostPlayedAdventure'] }}</td>
        <td>Least played adventure:</td>
        <td>{{ $stats['leastPlayedAdventure'] }}</td>
    </tr>
    <tr>
        <td>Adventures registered this week:</td>
        <td>{{ $stats['adventuresPlayedThisWeek'] }}</td>
        <td>Adventures registered last week:</td>
        <td>{{ $stats['adventuresPlayedLastWeek'] }}</td>
    </tr>
    </tbody>
</table>
<br>

<style>
    .date-picker {
        border: none;
        width: 100px;
    }
</style>
<h2 id="accumulated">Accumulated loot:</h2>
<span>Show loot submitted between <input type="text" id="accumulatedloot-datefrom" class="date-picker"> and <input
        type="text" id="accumulatedloot-dateto" class="date-picker"></span>
<div id="accumulatedloot" style="margin-top: 5px"></div>
<br>

<h2 id="latest">Latest loot:</h2>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Date/time</th>
        <th>User</th>
        <th>Adventure</th>
        <th>Loot</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    @foreach($useradventures as $useradventure)
    <tr id="latest_{{ $useradventure->ID }}">
        <td>{{ $useradventure->Date }}</td>
        <td>{{ $useradventure->User->username }}</td>
        <td>{{ $useradventure->Adventure->Name }}</td>
        <td>{{ $useradventure->lootText() }}</td>
        <td>
            <button class="btn btn-primary"
                    onClick="location.href='{{ action('LootController@edit', array($useradventure->ID)) }}'">Edit
            </button>
        </td>
        <td>
            <button class="btn btn-primary btn-deleteUserAdventure" data-toggle="modal" data-target="#deleteModal"
                    data-id="{{ $useradventure->ID }}">Delete
            </button>
        </td>
    </tr>
    @endforeach
</table>
{{ $useradventures->links('layouts.slider') }}
<br>

<h2 id="adventuresplayed">Adventures played:</h2>
<span>Show statistics for adventures registered between <input type="text" id="adventuresplayed-datefrom"
                                                               class="date-picker"> and
<input type="text" id="adventuresplayed-dateto" class="date-picker"></span>
<div id="adventuresplayedresult" style="margin-top: 5px"></div>


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
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token(); }}">
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


    //Code to handle price information.
    var pricedata = null;
    $(document).ready(function () {
        //Get price data from prices.mi5guild.com and save them for later use.
        $.getJSON("http://prices.mi5guild.com/prices/getjsonpprices?callback=?", function (result) {
            //response data are now in the result variable
            pricedata = result;
        });
    });

    $('#accumulatedloot .show-tooltip').hover(function (e) {
        if (pricedata != null) {
            //$(this).css('font-weight', 'bolder');
            var colindex = $(this).parent().parent().children().index($(this).parent()) - 1;
            var type = $(this).closest('tr').find('td:eq(' + colindex + ')').text();
            var item = findItem(type);
            if (item.length > 0) {

                var amount = parseInt($(this).text());
                var content = 'Min price: ' + (Math.round(item[0].min_price * amount * 10) / 10) + ' gc<br>';
                content += 'Avg price: ' + (Math.round(item[0].avg_price * amount * 10) / 10) + ' gc<br>';
                content += 'Max price: ' + (Math.round(item[0].max_price * amount * 10) / 10) + ' gc<br>';
                content += 'Source: prices.mi5guild.com';
                $(this).popover({ trigger: "hover", title: type, placement: "auto", content: content, html: true});
                $(this).popover('show');
            }
        }
    });

    function findItem(itemName) {
        return $.grep(pricedata, function (item) {
            return item.name.toLowerCase() == itemName.toLowerCase();
        });
    }

    $(document).ready(function () {
        $("#accumulatedloot-datefrom").datepicker({
            showOn: "button",
            buttonImage: "{{ URL::to('/assets/img/') }}/calendar.png",
            buttonImageOnly: true,
            dateFormat: "yy-mm-dd",
            onSelect: function () {
                updateAccumulatedLoot();
            }
        });
        $("#accumulatedloot-datefrom").datepicker().datepicker('setDate', new Date(2013, 0, 1));

        $("#accumulatedloot-dateto").datepicker({
            showOn: "button",
            buttonImage: "{{ URL::to('/assets/img/') }}/calendar.png",
            buttonImageOnly: true,
            dateFormat: "yy-mm-dd",
            onSelect: function () {
                updateAccumulatedLoot();
            }
        });
        $("#accumulatedloot-dateto").datepicker().datepicker('setDate', new Date());

        updateAccumulatedLoot();

        $("#adventuresplayed-datefrom").datepicker({
            showOn: "button",
            buttonImage: "{{ URL::to('/assets/img/') }}/calendar.png",
            buttonImageOnly: true,
            dateFormat: "yy-mm-dd",
            onSelect: function () {
                updateAdventuresPlayed();
            }
        });
        $("#adventuresplayed-datefrom").datepicker().datepicker('setDate', new Date(2013, 0, 1));

        $("#adventuresplayed-dateto").datepicker({
            showOn: "button",
            buttonImage: "{{ URL::to('/assets/img/') }}/calendar.png",
            buttonImageOnly: true,
            dateFormat: "yy-mm-dd",
            onSelect: function () {
                updateAdventuresPlayed();
            }
        });
        $("#adventuresplayed-dateto").datepicker().datepicker('setDate', new Date());

        updateAdventuresPlayed();
    });

    function updateAccumulatedLoot() {
        var datefrom = $("#accumulatedloot-datefrom").val();
        var dateto = $("#accumulatedloot-dateto").val();
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/accumulatedloot/'.$username) }}/" + datefrom + "/" + dateto,
            statusCode: {
                401: function () {
                    self.location = "/login";
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
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('/stats/adventuresplayed/'.$username) }}/" + datefrom + "/" + dateto,
            statusCode: {
                401: function () {
                    self.location = "/login";
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
@stop
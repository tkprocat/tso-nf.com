@extends('layouts.nomenu')

{{-- Content --}}
@section('content')
<style>
    .error {
        border-color: red;
    }
</style>
<h1>Add loot</h1>
<div class="panel panel-default">
    {{ Form::open(array(
    'action' => array('LootController@store'),
    'class' => 'form-horizontal',
    'role' => 'form',
    'id' => 'registerLoot'
    )) }}

    <div class="panel-heading" style="padding-top: 5px; padding-bottom: 5px">
        <ul class="errors">@foreach($errors->get('adventure') as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
        <div class="col-xs-3 col-md-2">
            {{ Form::label('adventure_id', 'Adventure:'); }}
        </div>
        <div style="text-align: left">
            {{ Form::select('adventure_id', $adventures->lists('name', 'id'), array('style' => 'width: 138px')); }}
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot1container">
                <label for="slot1options" style="padding-right: 5px">Slot 1:</label><select name="slot1" id="slot1options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot2container">
                <label for="slot1options" style="padding-right: 5px">Slot 2:</label><select name="slot2" id="slot2options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot3container">
                <label for="slot1options" style="padding-right: 5px">Slot 3:</label><select name="slot3" id="slot3options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot4container">
                <label for="slot1options" style="padding-right: 5px">Slot 4:</label><select name="slot4" id="slot4options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot5container">
                <label for="slot5options" style="padding-right: 5px">Slot 5:</label><select name="slot5" id="slot5options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot6container">
                <label for="slot6options" style="padding-right: 5px">Slot 6:</label><select name="slot6" id="slot6options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot7container">
                <label for="slot7options" style="padding-right: 5px">Slot 7:</label><select name="slot7" id="slot7options" style="width: 200px"></select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" style="padding-top: 10px" id="slot8container">
                <label for="slot8options" style="padding-right: 5px">Slot 8:</label><select name="slot8" id="slot8options" style="width: 200px"></select>
            </div>
        </div>
        <div style="text-align: center; margin-top: 15px">
            {{ Form::submit('Add', array('class' => 'btn btn-primary')); }}
        </div>
        {{ Form::close() }}
    </div>
</div>


<script>
var loot1selected = "";
var loot2selected = "";
var loot3selected = "";
var loot4selected = "";
var loot5selected = "";
var loot6selected = "";
var loot7selected = "";
var loot8selected = "";
var lootamount1selected = "";
var lootamount2selected = "";
var lootamount3selected = "";
var lootamount4selected = "";
var lootamount5selected = "";
var lootamount6selected = "";
var lootamount7selected = "";
var lootamount8selected = "";
var lootdata = null;

$(document).ready(function () {
    //Validate form submission.
    jQuery.validator.addMethod("slotRequired", function (value, element) {
        return (value != '0') && (value != '');
    }, "");

    if ($('[name="adventure"] option:selected').text() === '') {
        $('#slot1container').hide();
        $('#slot2container').hide();
        $('#slot3container').hide();
        $('#slot4container').hide();
        $('#slot5container').hide();
        $('#slot6container').hide();
        $('#slot7container').hide();
        $('#slot8container').hide();
    } else {
        adventureid = $('[name="adventure"]').val();
        $.ajax({
            type: "POST",
            cache: false,
            url: "{{ URL::to('/getJSONLoot') }}",
            data: {
                adventure: $('[name="adventure"] option:selected').val()
            }
        }).done(function (msg) {
            lootdata = msg;
            updateLootData();
            updateValidationRules()
        });
    }
});

$(document).on("submit", "form.registerLoot", function(event){
    alert(1);
});

$('[name="adventure"]').change(function (element) {
    adventureid = $('[name="adventure"]').val();
    $.ajax({
        type: "POST",
        url: "{{ URL::to('/getJSONLoot') }}",
        cache: false,
        data: {
            adventure: $('[name="adventure"] option:selected').val()
        }
    }).done(function (msg) {
        lootdata = msg;
        updateLootData();
        updateValidationRules()
    });
});

function updateLootData() {
    //Clear the drop downs
    $('#slot1options').empty();
    $('#slot2options').empty();
    $('#slot3options').empty();
    $('#slot4options').empty();
    $('#slot5options').empty();
    $('#slot6options').empty();
    $('#slot7options').empty();
    $('#slot8options').empty();

    var lastSlot = 0;
    $(lootdata).each(function () {
        if (lastSlot != this.Slot) {
            $('#slot' + this.Slot + 'options').append('<option value="0">Please select loot.</option>');
            lastSlot = this.Slot;
        }
        $('#slot' + this.Slot + 'options').append('<option value="'+this.ID+'">'+this.Type+' - '+this.Amount+'</option>');
    });

    for (var i = 1; i < 9; i++) {
        if ($('#slot' + i + 'options').html() === "")
            $('#slot' + i + 'container').hide();
        else {
            $('#slot' + i + 'container').show();

            //If a dropdown has the option for Nothing, select it by default since it's the likely option to be selected.
            /* jshint ignore:start */
            $('#slot'+i+'options option').filter(function() {
                return ($(this).text().indexOf('Nothing') >= 0);
            }).prop('selected', true);
            /* jshint ignore:end */

            //If a dropdown only has one option, select it.
            if ($('#slot'+i+'options option').length == 2)
                $('#slot'+i+'options').val($('#slot'+i+'options option:last').val());
        }
    }
}

function updateValidationRules() {
    //Clear any old rules.
    $('#registerLoot').removeData('validator');
    $("#registerLoot").validate();

    for (var i = 0; i <= 8; i++) {
        if ($('#slot' + i + 'container').is(":visible")) {
            $('[name="slot' + i +'"]').rules("add", { slotRequired: true });
        }
    }
}
</script>
@stop
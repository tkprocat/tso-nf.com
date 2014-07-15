@extends('layouts.default')

{{-- Content --}}
@section('content')
<style>
    .error {
        border-color: red;
    }
</style>
<h1>Add loot</h1>
<div class="panel panel-default">
    {{ Form::open(array('action' => array('LootController@store'), 'class' => 'form-horizontal', 'role' => 'form', 'id'
    => 'registerLoot' )) }}

    <div class="panel-heading" style="padding-top: 5px; padding-bottom: 5px">
        <ul style="color: red">
            @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
        <div class="col-xs-3 col-md-2">
            {{ Form::label('adventure_id', 'Adventure:'); }}
        </div>
        <div style="text-align: left">
            {{ Form::select('adventure_id', $adventures->lists('name', 'id'), '0', array('style' => 'width: 200px')) }}
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[1]) > 0 ? '' : 'display: none')}}" id="slot1container">
                <label for="slot1options" style="padding-right: 5px">Slot 1:</label>
                {{ Form::select('slot1', $loot_slots[1], 0, array('style' => 'width: 200px', "id" => "slot1options")) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[2]) > 0 ? '' : 'display: none')}}" id="slot2container">
                <label for="slot2options" style="padding-right: 5px">Slot 2:</label>
                {{ Form::select('slot2', $loot_slots[2], 0, array('style' => 'width: 200px', "id" => "slot2options"))}}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[3]) > 0 ? '' : 'display: none')}}" id="slot3container">
                <label for="slot3options" style="padding-right: 5px">Slot 3:</label>
                {{ Form::select('slot3', $loot_slots[3], 0, array('style' => 'width: 200px', "id" => "slot3options")) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[4]) > 0 ? '' : 'display: none')}}" id="slot4container">
                <label for="slot4options" style="padding-right: 5px">Slot 4:</label>
                {{ Form::select('slot4', $loot_slots[4], 0, array('style' => 'width: 200px', "id" => "slot4options")) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[5]) > 0 ? '' : 'display: none')}}" id="slot5container">
                <label for="slot5options" style="padding-right: 5px">Slot 5:</label>
                {{ Form::select('slot5', $loot_slots[5], 0, array('style' => 'width: 200px', "id" => "slot5options")) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[6]) > 0 ? '' : 'display: none')}}" id="slot6container">
                <label for="slot6options" style="padding-right: 5px">Slot 6:</label>
                {{ Form::select('slot6', $loot_slots[6], 0, array('style' => 'width: 200px', "id" => "slot6options")) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[7]) > 0 ? '' : 'display: none')}}" id="slot7container">
                <label for="slot7options" style="padding-right: 5px">Slot 7:</label>
                {{ Form::select('slot7', $loot_slots[7], 0, array('style' => 'width: 200px', "id" => "slot7options")) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6"
                 style="padding-top: 10px; {{ (count($loot_slots[8]) > 0 ? '' : 'display: none')}}" id="slot8container">
                <label for="slot8options" style="padding-right: 5px">Slot 8:</label>
                {{ Form::select('slot8', $loot_slots[8], 0, array('style' => 'width: 200px', "id" => "slot8options")) }}
            </div>
        </div>
        <div style="text-align: center; margin-top: 15px">
            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
            <button
                onclick="javascript:void window.open('./createpopup','1392066459886','width=1005,height=350,toolbar=0,menubar=0,location=0,status=1,scrollbars=0,resizable=1,left=0,top=0');return false;"
                class="btn btn-primary">Open Popup
            </button>
        </div>
        {{ Form::close() }}
    </div>
</div>


<script>
    $('#adventure_id').change(function (element) {
        adventureid = $('#adventure_id').val();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('/loot/getJSONLoot') }}",
            cache: false,
            data: {
                adventure: $('#adventure_id option:selected').val()
            }
        }).done(function (msg) {
            lootdata = msg;
            updateLootData();
        });
    });

    function updateLootData() {
        //Clear the drop downs
        $('[id$="options"]').empty();


        var lastSlot = 0;
        $(lootdata).each(function () {
            if (lastSlot != this.slot) {
                $('#slot' + this.slot + 'options').append('<option value="0">Please select loot.</option>');
                lastSlot = this.slot;
            }
            $('#slot' + this.slot + 'options').append('<option value="' + this.id + '">' + this.type + ' - ' + this.amount + '</option>');
        });

        for (var i = 1; i < 9; i++) {
            if ($('#slot' + i + 'options').html() === "")
                $('#slot' + i + 'container').hide();
            else {
                $('#slot' + i + 'container').show();

                //If a dropdown has the option for Nothing, select it by default since it's the likely option to be selected.
                /* jshint ignore:start */
                $('#slot' + i + 'options option').filter(function () {
                    return ($(this).text().indexOf('Nothing') >= 0);
                }).prop('selected', true);
                /* jshint ignore:end */

                //If a dropdown only has one option, select it.
                if ($('#slot' + i + 'options option').length == 2)
                    $('#slot' + i + 'options').val($('#slot' + i + 'options option:last').val());
            }
        }
    }
</script>
@stop
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
        <div class="form-group">
            {{ Form::label('adventure_id', 'Adventure:', array('class' => 'col-lg-2 control-label')); }}
            <div style="col-lg-10">
                {{ Form::select('adventure_id', $adventures->lists('name', 'id'), '0', array('style' => 'width: 200px',
                'class' => 'form-control')) }}
            </div>
        </div>
    </div>
    <div class="panel-body" style="margin-left: 20px">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 form-group" style="padding-top: 10px; {{ (count($loot_slots[1]) > 0 ? '' : 'display: none')}}" id="slot1container">
                {{ Form::label('slot1options', 'Slot 1:', array('class' => 'control-label')) }}
                {{ Form::select('slot1', $loot_slots[1], 0, array('style' => 'width: 200px', "id" => "slot1options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[2]) > 0 ? '' : 'display: none')}}" id="slot2container">
                {{ Form::label('slot2options', 'Slot 2:', array('class' => 'control-label')) }}
                {{ Form::select('slot2', $loot_slots[2], 0, array('style' => 'width: 200px', "id" => "slot2options", 'class' => 'form-control'))}}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[3]) > 0 ? '' : 'display: none')}}" id="slot3container">
                {{ Form::label('slot3options', 'Slot 3:', array('class' => 'control-label')) }}
                {{ Form::select('slot3', $loot_slots[3], 0, array('style' => 'width: 200px', "id" => "slot3options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[4]) > 0 ? '' : 'display: none')}}" id="slot4container">
                {{ Form::label('slot14options', 'Slot 4:', array('class' => 'control-label')) }}
                {{ Form::select('slot4', $loot_slots[4], 0, array('style' => 'width: 200px', "id" => "slot4options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[5]) > 0 ? '' : 'display: none')}}" id="slot5container">
                {{ Form::label('slot5options', 'Slot 5:', array('class' => 'control-label')) }}
                {{ Form::select('slot5', $loot_slots[5], 0, array('style' => 'width: 200px', "id" => "slot5options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[6]) > 0 ? '' : 'display: none')}}" id="slot6container">
                {{ Form::label('slot6options', 'Slot 6:', array('class' => 'control-label')) }}
                {{ Form::select('slot6', $loot_slots[6], 0, array('style' => 'width: 200px', "id" => "slot6options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[7]) > 0 ? '' : 'display: none')}}" id="slot7container">
                {{ Form::label('slot7options', 'Slot 7:', array('class' => 'control-label')) }}
                {{ Form::select('slot7', $loot_slots[7], 0, array('style' => 'width: 200px', "id" => "slot7options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[8]) > 0 ? '' : 'display: none')}}" id="slot8container">
                {{ Form::label('slot8options', 'Slot 8:', array('class' => 'control-label')) }}
                {{ Form::select('slot8', $loot_slots[8], 0, array('style' => 'width: 200px', "id" => "slot8options", 'class' => 'form-control')) }}
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
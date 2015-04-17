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
        <div class="form-horizontal">
            <div class="form-group">
                {{ Form::label('adventure_id', 'Adventure:', array('class' => 'control-label col-sm-2')); }}
                <div class="col-sm-3">
                {{ Form::select('adventure_id', $adventures->lists('type_and_name', 'id'), '0', array('class' => 'form-control')) }}
                </div>
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

            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[9]) > 0 ? '' : 'display: none')}}" id="slot9container">
                {{ Form::label('slot9options', 'Slot 9:', array('class' => 'control-label')) }}
                {{ Form::select('slot9', $loot_slots[9], 0, array('style' => 'width: 200px', "id" => "slot9options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[10]) > 0 ? '' : 'display: none')}}" id="slot10container">
                {{ Form::label('slot10options', 'Slot 10:', array('class' => 'control-label')) }}
                {{ Form::select('slot10', $loot_slots[10], 0, array('style' => 'width: 200px', "id" => "slot10options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[11]) > 0 ? '' : 'display: none')}}" id="slot11container">
                {{ Form::label('slot11options', 'Slot 11:', array('class' => 'control-label')) }}
                {{ Form::select('slot11', $loot_slots[11], 0, array('style' => 'width: 200px', "id" => "slot11options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[12]) > 0 ? '' : 'display: none')}}" id="slot12container">
                {{ Form::label('slot12options', 'Slot 12:', array('class' => 'control-label')) }}
                {{ Form::select('slot12', $loot_slots[12], 0, array('style' => 'width: 200px', "id" => "slot12options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[13]) > 0 ? '' : 'display: none')}}" id="slot13container">
                {{ Form::label('slot13options', 'Slot 13:', array('class' => 'control-label')) }}
                {{ Form::select('slot13', $loot_slots[13], 0, array('style' => 'width: 200px', "id" => "slot13options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[14]) > 0 ? '' : 'display: none')}}" id="slot14container">
                {{ Form::label('slot14options', 'Slot 14:', array('class' => 'control-label')) }}
                {{ Form::select('slot14', $loot_slots[14], 0, array('style' => 'width: 200px', "id" => "slot14options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[15]) > 0 ? '' : 'display: none')}}" id="slot15container">
                {{ Form::label('slot15options', 'Slot 15:', array('class' => 'control-label')) }}
                {{ Form::select('slot15', $loot_slots[15], 0, array('style' => 'width: 200px', "id" => "slot15options", 'class' => 'form-control')) }}
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[16]) > 0 ? '' : 'display: none')}}" id="slot16container">
                {{ Form::label('slot16options', 'Slot 16:', array('class' => 'control-label')) }}
                {{ Form::select('slot16', $loot_slots[16], 0, array('style' => 'width: 200px', "id" => "slot16options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[17]) > 0 ? '' : 'display: none')}}" id="slot17container">
                {{ Form::label('slot17options', 'Slot 17:', array('class' => 'control-label')) }}
                {{ Form::select('slot17', $loot_slots[17], 0, array('style' => 'width: 200px', "id" => "slot17options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[18]) > 0 ? '' : 'display: none')}}" id="slot18container">
                {{ Form::label('slot18options', 'Slot 18:', array('class' => 'control-label')) }}
                {{ Form::select('slot18', $loot_slots[18], 0, array('style' => 'width: 200px', "id" => "slot18options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[19]) > 0 ? '' : 'display: none')}}" id="slot19container">
                {{ Form::label('slot19options', 'Slot 19:', array('class' => 'control-label')) }}
                {{ Form::select('slot19', $loot_slots[19], 0, array('style' => 'width: 200px', "id" => "slot19options", 'class' => 'form-control')) }}
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 form-group"
                 style="padding-top: 10px; {{ (count($loot_slots[20]) > 0 ? '' : 'display: none')}}" id="slot20container">
                {{ Form::label('slot20options', 'Slot 20:', array('class' => 'control-label')) }}
                {{ Form::select('slot20', $loot_slots[20], 0, array('style' => 'width: 200px', "id" => "slot20options", 'class' => 'form-control')) }}
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

{{ HTML::style('/assets/bower/bootstrap-combobox/css/bootstrap-combobox.css') }}
<script type="text/javascript" src="{{ URL::to('/') }}/assets/bower/bootstrap-combobox/js/bootstrap-combobox.js"></script>
<script>
    $(document).ready(function() {
        //Silly way of doing this.
        $('#adventure_id').prepend("<option value='' selected='selected'>Please select an adventure</option>");
        $('#adventure_id').combobox();

    });

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
                $('#slot' + this.slot + 'options').parent().addClass('has-warning');
                lastSlot = this.slot;
            }
            $('#slot' + this.slot + 'options').append('<option value="' + this.id + '">' + this.type + ' - ' + this.amount + '</option>');
        });

        for (var i = 1; i < 20; i++) {
            if ($('#slot' + i + 'options').html() === "")
                $('#slot' + i + 'container').hide();
            else {
                $('#slot' + i + 'container').show();

                //If a dropdown has the option for Nothing, select it by default since it's the likely option to be selected.
                /* jshint ignore:start */
                $('#slot' + i + 'options option').filter(function () {
                    if ($(this).text().indexOf('Nothing') >= 0)
                    {
                        $('#slot' + i + 'options').parent().removeClass('has-warning');
                        return true;
                    }
                    return false;
                }).prop('selected', true);
                 /* jshint ignore:end */

                //If a dropdown only has one option, select it.
                if ($('#slot' + i + 'options option').length == 2) {
                    $('#slot' + i + 'options').val($('#slot' + i + 'options option:last').val());
                    $('#slot' + i + 'options').parent().removeClass('has-warning');
                }
            }
        }
    }
</script>
@stop
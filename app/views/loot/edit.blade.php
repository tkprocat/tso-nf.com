@extends('layouts.default')

{{-- Content --}}
@section('content')
<h1>Update loot</h1>

<div class="well">
{{ Form::open(array(
'action' => array('LootController@update', $useradventure->id),
'method' => 'put',
'class' => 'form-horizontal',
'role' => 'form',
'id' => 'updateLoot'
)) }}
{{ Form::hidden('useradventureid', $useradventure->id) }}
<div class="row" style="margin-top: 5px">
    <ul class="errors">@foreach($errors->get('adventure') as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
    <div class="col-xs-3 col-md-2">
        {{ Form::label('adventure_id', 'Adventure:'); }}
    </div>
    <div style="text-align: left">
        <div style="text-align: left">
            {{ Form::select('adventure_id', $adventures->lists('name', 'id'), array('style' => 'width: 138px')); }}
        </div>
    </div>
</div>
<div class="row" style="margin-top: 15px">
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot1container" {{ ($useradventure->loot()->slot(1)->first() != null) ? '' : 'style="display: none"' }}>
        <label for="slot1" style="padding-right: 5px">Slot 1:</label>
        @if ($useradventure->loot()->slot(1)->first() != null)
        {{ Form::select('slot1', $loot_slots[1], $useradventure->loot()->slot(1)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot1', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot2container" {{ ($useradventure->loot()->slot(2)->first() != null) ? '' : 'style="display: none"' }}>
        <label for="slot2" style="padding-right: 5px">Slot 2:</label>
        @if ($useradventure->loot()->slot(2)->first() != null)
        {{ Form::select('slot2', $loot_slots[2], $useradventure->loot()->slot(2)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot2', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot3container" {{ ($useradventure->loot()->slot(3)->first() != null) ? '' : 'style="display: none"' }}>
    <label for="slot3" style="padding-right: 5px">Slot 3:</label>
        @if ($useradventure->loot()->slot(3)->first() != null)
        {{ Form::select('slot3', $loot_slots[3], $useradventure->loot()->slot(3)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot3', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot4container" {{ ($useradventure->loot()->slot(4)->first() != null) ? '' : 'style="display: none"' }}>
    <label for="slot4" style="padding-right: 5px">Slot 4:</label>
        @if ($useradventure->loot()->slot(4)->first() != null)
        {{ Form::select('slot4', $loot_slots[4], $useradventure->loot()->slot(4)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot4', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
</div>

<div class="row" style="margin-top: 15px">
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot3container" {{ ($useradventure->loot()->slot(5)->first() != null) ? '' : 'style="display: none"' }}>
        <label for="slot5" style="padding-right: 5px">Slot 5:</label>
        @if ($useradventure->loot()->slot(5)->first() != null)
        {{ Form::select('slot5', $loot_slots[5], $useradventure->loot()->slot(5)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot5', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot3container" {{ ($useradventure->loot()->slot(6)->first() != null) ? '' : 'style="display: none"' }}>
        <label for="slot6" style="padding-right: 5px">Slot 6:</label>
        @if ($useradventure->loot()->slot(6)->first() != null)
        {{ Form::select('slot6', $loot_slots[6], $useradventure->loot()->slot(6)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot6', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot3container" {{ ($useradventure->loot()->slot(7)->first() != null) ? '' : 'style="display: none"' }}>
        <label for="slot7" style="padding-right: 5px">Slot 7:</label>
        @if ($useradventure->loot()->slot(7)->first() != null)
        {{ Form::select('slot7', $loot_slots[7], $useradventure->loot()->slot(7)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot7', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6" id="slot3container" {{ ($useradventure->loot()->slot(8)->first() != null) ? '' : 'style="display: none"' }}>
        <label for="slot8" style="padding-right: 5px">Slot 8:</label>
        @if ($useradventure->loot()->slot(8)->first() != null)
        {{ Form::select('slot8', $loot_slots[8], $useradventure->loot()->slot(8)->first()->adventure_loot_id, array('style'
        => 'width: 200px')); }}
        @else
        {{ Form::select('slot8', array(), 0, array('style' => 'width: 200px')); }}
        @endif
    </div>
</div>
<div class="row">
    <div style="text-align: center; margin-top: 15px">
        {{ Form::submit('Update', array('class' => 'btn btn-primary')); }}
    </div>
    {{ Form::close() }}
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

/* $(document).ready(function () {
    //Validate form submission.
    jQuery.validator.addMethod("slotRequired", function (value, element) {
        return (value != '-1') && (value != '');
    }, "");

    jQuery.validator.addMethod("slotAmountRequired", function (value, element) {
        return (value != '-1') && (value != '');
    }, "");

    if ($('[name="adventure_id"] option:selected').text() == '') {
        $('#slot1container').hide();
        $('#slot2container').hide();
        $('#slot3container').hide();
        $('#slot4container').hide();
        $('#slot5container').hide();
        $('#slot6container').hide();
        $('#slot7container').hide();
        $('#slot8container').hide();
    } else {
        //if we're trying to update, get the data stored for that submission and return it.
        if (useradventureid > 0) {
            $.ajax({
                type: "Post",
                url: "{{ URL::to('/getUserAdventure') }}",
                dataType: 'json',
                cache: false,
                data: {
                    user_adventure_id: useradventureid
                }
            }).done(function (data) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: "{{ URL::to('/getJSONLoot') }}",
                    data: {
                        adventure: data.useradventure.AdventureID
                    }
                }).done(function (msg) {
                    lootdata = msg;
                    updateLootData();
                    loadEditData(data);
                });
            });
        }
    }
}); */

$('[name="adventure_id"]').change(function (element) {
    adventureid = $('[name="adventure_id"]').val();
    clearSelectedLoot();
    $.ajax({
        type: "POST",
        url: "{{ URL::to('/getJSONLoot') }}",
        cache: false,
        data: {
            adventure: $('[name="adventure_id"] option:selected').val()
        }
    }).done(function (msg) {
        lootdata = msg;
        updateLootData();
        loadAmountData(1);
        loadAmountData(2);
        loadAmountData(3);
        loadAmountData(4);
        loadAmountData(5);
        loadAmountData(6);
        loadAmountData(7);
        loadAmountData(8);
    });
});

$('[name="slot1"]').change(function () {
    loot1selected = "";
    lootamount1selected = "";
    loadAmountData(1);
});

$('[name="slot2"]').change(function () {
    loot2selected = "";
    lootamount2selected = "";
    loadAmountData(2);
});

$('[name="slot3"]').change(function () {
    loot3selected = "";
    lootamount3selected = "";
    loadAmountData(3);
});

$('[name="slot4"]').change(function () {
    loot4selected = "";
    lootamount4selected = "";
    loadAmountData(4);
});

$('[name="slot5"]').change(function () {
    loot5selected = "";
    lootamount5selected = "";
    loadAmountData(5);
});

$('[name="slot6"]').change(function () {
    loot6selected = "";
    lootamount6selected = "";
    loadAmountData(6);
});

$('[name="slot7"]').change(function () {
    loot7selected = "";
    lootamount7selected = "";
    loadAmountData(7);
});

$('[name="slot8"]').change(function () {
    loot8selected = "";
    lootamount8selected = "";
    loadAmountData(8);
});

function loadAmountData($slot) {
    $('[name="slot' + $slot + '_amount"]').empty();
    $(lootdata).each(function () {
        if ((this.slot == $slot) && (this.type == $('[name="slot' + $slot + '"] option:selected').text())) {
            if ($('[name="slot' + $slot + '_amount"] option[value="' + this.amount + '"]').length == 0) {
                var $option = $("<option />");
                $option.attr("value", this.amount).text(this.amount);
                $('[name="slot' + $slot + '_amount"]').append($option);
            }
        }
    });
    if ($('[name="slot' + $slot + '_amount"] option').length > 1)
        $('[name="slot' + $slot + '_amount"]').prepend("<option value='-1'>Please select amount</option>").val('');
    $('[name="slot' + $slot + '_amount"]').val($('[name="slot' + $slot + '_amount"] option:contains(' + eval('lootamount' + $slot + 'selected') + ')').val());
}

function updateLootData() {
    //Clear the drop downs
    $('[name="slot1"]').empty();
    $('[name="slot2"]').empty();
    $('[name="slot3"]').empty();
    $('[name="slot4"]').empty();
    $('[name="slot5"]').empty();
    $('[name="slot6"]').empty();
    $('[name="slot7"]').empty();
    $('[name="slot8"]').empty();
    $('[name="slot1_amount"]').empty();
    $('[name="slot2_amount"]').empty();
    $('[name="slot3_amount"]').empty();
    $('[name="slot4_amount"]').empty();
    $('[name="slot5_amount"]').empty();
    $('[name="slot6_amount"]').empty();
    $('[name="slot7_amount"]').empty();
    $('[name="slot8_amount"]').empty();
    $('#slot1container').show();
    $('#slot2container').show();
    $('#slot3container').show();
    $('#slot4container').show();
    $('#slot5container').show();
    $('#slot6container').show();
    $('#slot7container').show();
    $('#slot8container').show();

    $(lootdata).each(function () {
        if ($('[name="slot' + this.slot + '"] option[value="' + this.type + '"]').length == 0) {
            $('[name="slot' + this.slot + '"]').append('<option value="' + this.type + '">' + this.type + '</option>').val('');
        }
    });

    if ($('[name="slot1"] option').length == 0)
        $('#slot1container').hide();
    else {
        $('[name="slot1"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot1"]').val($('[name="slot1"] option:contains(' + loot1selected + ')').val());
    }

    if ($('[name="slot2"] option').length == 0)
        $('#slot2container').hide();
    else {
        $('[name="slot2"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot2"]').val($('[name="slot2"] option:contains(' + loot2selected + ')').val());
    }

    if ($('[name="slot3"] option').length == 0)
        $('#slot3container').hide();
    else {
        $('[name="slot3"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot3"]').val($('[name="slot3"] option:contains(' + loot3selected + ')').val());
    }

    if ($('[name="slot4"] option').length == 0)
        $('#slot4container').hide();
    else {
        $('[name="slot4"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot4"]').val($('[name="slot4"] option:contains(' + loot4selected + ')').val());
    }

    if ($('[name="slot5"] option').length == 0)
        $('#slot5container').hide();
    else {
        $('[name="slot5"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot5"]').val($('[name="slot5"] option:contains(' + loot5selected + ')').val());
    }

    if ($('[name="slot6"] option').length == 0)
        $('#slot6container').hide();
    else {
        $('[name="slot6"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot6"]').val($('[name="slot6"] option:contains(' + loot6selected + ')').val());
    }

    if ($('[name="slot7"] option').length == 0)
        $('#slot7container').hide();
    else {
        $('[name="slot7"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot7"]').val($('[name="slot7"] option:contains(' + loot7selected + ')').val());
    }

    if ($('[name="slot8"] option').length == 0)
        $('#slot8container').hide();
    else {
        $('[name="slot8"]').prepend("<option value='-1'>Please select loot</option>").val('');
        $('[name="slot8"]').val($('[name="slot8"] option:contains(' + loot8selected + ')').val());
        if ((loot8selected == '') && ((adventureid == 1) || (adventureid == 21))) {
            $('[name="slot8"]').val('Nothing');
            $('[name="slot8_amount"]').append('<option value="1">1</option>').val('1');
        }
    }

    /* updateValidationRules(); */
}

/* function updateValidationRules() {
    //Clear any old rules.
    $('#updateLoot').removeData('validator');
    $("#updateLoot").validate();

    for (i = 0; i <= 8; i++) {
        if ($('#slot' + i + 'container').is(":visible")) {
            $('[name="slot' + i + '"]').rules("add", {slotRequired: true});
            $('[name="slot' + i + '_amount"]').rules("add", {slotAmountRequired: true});
        }
    }
} */

function loadEditData(data) {
    if (data.useradventure[0] != null) //Advenute dropdown
    {
        $('[name="adventure_id"]').val(data.useradventure[0].AdventureID);
    }

    for (i = 0; i <= 8; i++) {
        if (data.useradventureloot[i] != null) // Slot 1
        {
            $('[name="slot' + data.useradventureloot[i].Slot + '"]').val(data.useradventureloot[i].Type);
            loadEditAmountData(data.useradventureloot[i].Slot, data.useradventureloot[i].Type);
            $('[name="slot' + data.useradventureloot[i].Slot + '_amount"]').val(data.useradventureloot[i].Amount);
        }
    }
}

function loadEditAmountData($slot, $type) {
    $('[name="slot' + $slot + '_amount"]').empty();
    $(lootdata).each(function () {
        if ((this.slot == $slot) && (this.type == $type)) {
            if ($('[name="slot' + $slot + '_amount"] option[value="' + this.amount + '"]').length == 0) {
                var $option = $("<option />");
                $option.attr("value", this.amount).text(this.amount);
                $('[name="slot' + $slot + '_amount"]').append($option);
            }
        }
    });
}

function clearSelectedLoot() {
    loot1selected = "";
    loot2selected = "";
    loot3selected = "";
    loot4selected = "";
    loot5selected = "";
    loot6selected = "";
    loot7selected = "";
    loot8selected = "";
    lootamount1selected = "";
    lootamount2selected = "";
    lootamount3selected = "";
    lootamount4selected = "";
    lootamount5selected = "";
    lootamount6selected = "";
    lootamount7selected = "";
    lootamount8selected = "";
}
</script>
@stop
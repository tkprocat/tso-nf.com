@extends('layouts.default')

{{-- Content --}}
@section('content')
<h1>Update loot</h1>

<div class="panel panel-default">
    {{ Form::open(array(
    'action' => array('LootController@update', $useradventure->id),
    'method' => 'put',
    'class' => 'form-horizontal',
    'role' => 'form',
    'id' => 'updateLoot'
    )) }}
    {{ Form::hidden('useradventureid', $useradventure->id) }}
    <div class="panel-heading" style="padding-top: 5px; padding-bottom: 5px">
        <ul class="errors">@foreach($errors->get('adventure') as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
        <div class="form-group">
            {{ Form::label('adventure_id', 'Adventure:',array('class' => 'col-lg-2 control-label')); }}
            <div style="col-lg-10">
                {{ Form::select('adventure_id', $adventures->lists('type_and_name', 'id'), $useradventure->adventure_id,
                array('style' => 'width: 200px', 'class' => 'form-control')); }}
            </div>
        </div>
    </div>

    <div class="panel-body" style="margin-left: 20px">
        <div class="row" style="margin-top: 15px">
        @for($i = 1; $i <= 20;$i++)

            <div class="col-lg-3 col-md-4 col-sm-6 form-group" id="slot{{ $i }}container"{{ ($useradventure->adventure()->first()->loot()->slot($i)->first() != null) ? '' : 'style="display: none"' }}>
            <label for="slot1" style="padding-right: 5px">Slot {{ $i }}:</label>
            @if ($useradventure->loot()->slot($i)->first() != null)
            {{ Form::select('slot'.$i, $loot_slots[$i], $useradventure->loot()->slot($i)->first()->adventure_loot_id, array('style'=> 'width: 200px', 'class' => 'form-control')); }}
            @else
            {{ Form::select('slot'.$i, $loot_slots[$i], 0, array('style' => 'width: 200px' , 'class' => 'form-control')); }}
            @endif
            </div>
        @endfor
        </div>
        <div class="row">
            <div style="text-align: center; margin-top: 15px">
                {{ Form::submit('Update', array('class' => 'btn btn-primary')); }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
var loot_selected = {};
var lootamount_selected = {};
var lootdata = null;

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
        for (i = 1; i <= 20; i++) {
            loadAmountData(i);
        }
    });
});

for (i = 1; i <= 20; i++) {
    $('[name="slot'+i+'"]').change(function () {
        loot_selected[i] = '';
        lootamount_selected[i] = '';
        loadAmountData(i);
    });
}

function loadAmountData(slot) {
    $('[name="slot' + slot + '_amount"]').empty();
    $(lootdata).each(function () {
        if ((this.slot == slot) && (this.type == $('[name="slot' + slot + '"] option:selected').text())) {
            if ($('[name="slot' + slot + '_amount"] option[value="' + this.amount + '"]').length == 0) {
                var $option = $("<option />");
                $option.attr("value", this.amount).text(this.amount);
                $('[name="slot' + slot + '_amount"]').append($option);
            }
        }
    });
    if ($('[name="slot' + slot + '_amount"] option').length > 1)
        $('[name="slot' + slot + '_amount"]').prepend("<option value='-1'>Please select amount</option>").val('');
    $('[name="slot' + slot + '_amount"]').val($('[name="slot' + slot + '_amount"] option:contains(' + eval('lootamount' + slot + 'selected') + ')').val());
}

function updateLootData() {
    //Clear the drop downs
     for (i = 1; i <= 20; i++) {
        $('[name="slot'+i+'"]').empty();
        $('[name="slot'+i+'_amount"]').empty();
        $('#slot'+i+'container').show();
    }

    $(lootdata).each(function () {
        if ($('[name="slot' + this.slot + '"] option[value="' + this.type + '"]').length == 0) {
            $('[name="slot' + this.slot + '"]').append('<option value="' + this.type + '">' + this.type + '</option>').val('');
        }
    });

    for (i = 1; i <= 20; i++) {
        if ($('[name="slot'+i+'"] option').length == 0)
            $('#slot8container').hide();
        else {
            $('[name="slot'+i+'"]').prepend("<option value='-1'>Please select loot</option>").val('');
            $('[name="slot'+i+'"]').val($('[name="slot'+i+'"] option:contains(' + loot_selected[i] + ')').val());
            if ((loot8selected == '') && ((adventureid == 1) || (adventureid == 21))) {
                $('[name="slot'+i+'"]').val('Nothing');
                $('[name="slot'+i+'_amount"]').append('<option value="1">1</option>').val('1');
            }
        }
    }
}

function loadEditData(data) {
    if (data.useradventure[0] != null) //Advenute dropdown
    {
        $('[name="adventure_id"]').val(data.useradventure[0].AdventureID);
    }

    for (i = 0; i <= 20; i++) {
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
    loot_selected = {};
    lootamount_selected = {};
}
</script>
@stop
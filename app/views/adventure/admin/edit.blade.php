@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Update adventure
@stop

{{-- Content --}}
@section('content')
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<h4>Update adventure</h4>
<div class="well">
    {{ Form::open(array('action' => array('AdminAdventureController@update', $adventure->id), 'method' => 'put', 'class' => "form-horizontal")) }}
        {{ Form::hidden("adventure_id", $adventure->id) }}
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            {{ Form::label('name', 'Name:', array('class' =>  'col-sm-2 control-label')) }}
            <div class="col-sm-10">
            {{ Form::text('name', $adventure->name, array('class' => 'form-control', 'placeholder' => 'Adventure name')) }}
            </div>
            {{ ($errors->has('name') ? $errors->first('name') : '') }}
        </div>

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-1">Slot</div>
            <div class="col-sm-4">Item name</div>
            <div class="col-sm-2">Amount</div>
        </div>

        <div class="form-group ">
        @foreach($adventure->loot as $loot)
            <div class="col-sm-2">{{ Form::hidden("items[$loot->slot][id]", $loot->id) }}</div>
            <div class="col-sm-1  {{ ($errors->has("slot[$loot->slot]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$loot->slot][slot]", $loot->slot, array('class' => 'form-control', 'placeholder' => 'Slot')) }}
            </div>
            <div class="col-sm-4  {{ ($errors->has("type[$loot->slot]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$loot->slot][type]", $loot->type, array('class' => 'form-control items', 'placeholder' => 'Item')) }}
            </div>
            <div class="col-sm-2  {{ ($errors->has("amount[$loot->slot]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$loot->slot][amount]", $loot->amount, array('class' => 'form-control', 'placeholder' => 'Amount')) }}
            </div>
        @endforeach
        @for ($i = 1; $i < 5; $i++)
            <div div class="col-sm-2"></div>
            <div div class="col-sm-1  {{ ($errors->has("slot[$i]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$i][slot]", null, array('class' => 'form-control', 'placeholder' => 'Slot')) }}
            </div>
            <div div class="col-sm-4  {{ ($errors->has("type[$i]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$i][type]", null, array('class' => 'form-control items', 'placeholder' => 'Item')) }}
            </div>
            <div div class="col-sm-2  {{ ($errors->has("amount[$i]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$i][amount]", null, array('class' => 'form-control', 'placeholder' => 'Amount')) }}
            </div>
        @endfor
        </div>
        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
<script>
    var items = [ ];
    $(document).ready(function() {
        $.get( "/admin/adventure/getItemTypes", function( data ) {
            items = data;
        });
    });


    $(".items").autocomplete({
        source: function( request, response ) {
            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
            response( $.grep( items, function( item ){
                return matcher.test( item );
            }) );
        }
    });
</script>
@stop
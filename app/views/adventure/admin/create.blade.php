@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Add new adventure
@stop

{{-- Content --}}
@section('content')
<h4>Add new adventure</h4>
<div class="well">
    {{ Form::open(array('action' => 'AdminAdventureController@store', 'class' => "form-horizontal")) }}

        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}" for="name">
            {{ Form::label('name', 'Name:', array('class' =>  'col-sm-2 control-label')) }}
            <div div class="col-sm-10">
            {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Adventure name')) }}
            </div>
            {{ ($errors->has('name') ? $errors->first('name') : '') }}
        </div>

        <div class="row">
            <div div class="col-sm-2"></div>
            <div div class="col-sm-1">Slot</div>
            <div div class="col-sm-4">Item name</div>
            <div div class="col-sm-2">Amount</div>
        </div>

        @for ($i = 1; $i < 50; $i++)
        <div class="form-group ">
            <div div class="col-sm-2"></div>
            <div div class="col-sm-1  {{ ($errors->has("slot[$i]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$i][slot]", null, array('class' => 'form-control', 'placeholder' => 'Slot')) }}
            </div>
            <div div class="col-sm-4  {{ ($errors->has("type[$i]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$i][type]", null, array('class' => 'form-control', 'placeholder' => 'Item')) }}
            </div>
            <div div class="col-sm-2  {{ ($errors->has("amount[$i]")) ? 'has-error' : '' }}">
                {{ Form::text("items[$i][amount]", null, array('class' => 'form-control', 'placeholder' => 'Amount')) }}
            </div>
        </div>
        @endfor
        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop
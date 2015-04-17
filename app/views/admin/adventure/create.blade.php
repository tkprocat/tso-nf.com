@extends('layouts.default-admin')

{{-- Web site Title --}}
@section('title')
@parent
Add new adventure
@stop

{{-- Content --}}
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li><a href="/admin/">Overview</a></li>
                    <li><a href="/admin/users">Users</a></li>
                    <li><a href="/admin/adventures">Adventures</a></li>
                    <li class="active"><a href="/admin/adventures/create">- Add new adventure <span class="sr-only">(current)</span></a></li>
                    <li><a href="/admin/prices">Prices</a></li>
                    <li><a href="/admin/prices/create">- Add new item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
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

                    <div class="form-group {{ ($errors->has('type')) ? 'has-error' : '' }}">
                        {{ Form::label('type', 'Type:', array('class' =>  'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            {{ Form::text('type', null, array('class' => 'form-control', 'placeholder' => 'Adventure type')) }}
                        </div>
                        {{ ($errors->has('type') ? $errors->first('type') : '') }}
                    </div>

                    <div class="form-group {{ ($errors->has('disabled')) ? 'has-error' : '' }}">
                        {{ Form::label('disabled', 'Disabled:', array('class' =>  'col-sm-2 control-label')) }}
                        <div class="col-sm-10">
                            {{ Form::checkbox('disabled', null, null, array('class' => 'checkbox-inline')) }}
                        </div>
                        {{ ($errors->has('disabled') ? $errors->first('disabled') : '') }}
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
                                {{ Form::text("items[$i][type]", null, array('class' => 'form-control items', 'placeholder' => 'Item')) }}
                            </div>
                            <div div class="col-sm-2  {{ ($errors->has("amount[$i]")) ? 'has-error' : '' }}">
                                {{ Form::text("items[$i][amount]", null, array('class' => 'form-control', 'placeholder' => 'Amount')) }}
                            </div>
                        </div>
                    @endfor
                    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
                <script>
                    var items = [ ];
                    $(document).ready(function() {
                        $.get( "/admin/adventures/getItemTypes", function( data ) {
                            items = data;
                        });



                        $(".items").autocomplete({
                            source: function( request, response ) {
                                var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                                response( $.grep( items, function( item ){
                                    return matcher.test( item );
                                }) );
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@stop
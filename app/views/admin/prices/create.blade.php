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
                    <li><a href="/admin/adventures/create">- Add new adventure</a></li>
                    <li><a href="/admin/adventures">Prices</a></li>
                    <li class="active"><a href="/admin/prices/create">- Add new item <span class="sr-only">(current)</span></a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h4>Add new adventure</h4>
                <div class="well">
                    {{ Form::open(array('action' => 'AdminAdventureController@store', 'class' => "form-horizontal")) }}

                    <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}" for="name">
                        {{ Form::label('name', 'Name:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-4">
                            {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Adventure name')) }}
                        </div>
                        {{ ($errors->has('name') ? $errors->first('name') : '') }}
                    </div>

                    <div class="form-group {{ ($errors->has('minprice')) ? 'has-error' : '' }}" for="minprice">
                        {{ Form::label('minprice', 'Min. price:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-2">
                            {{ Form::text('minprice', null, array('class' => 'form-control', 'placeholder' => 'Min. price')) }}
                        </div>
                        {{ ($errors->has('minprice') ? $errors->first('minprice') : '') }}
                    </div>

                    <div class="form-group {{ ($errors->has('avgprice')) ? 'has-error' : '' }}" for="avgprice">
                        {{ Form::label('avgprice', 'Avg. price:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-2">
                            {{ Form::text('avgprice', null, array('class' => 'form-control', 'placeholder' => 'Avg. price')) }}
                        </div>
                        {{ ($errors->has('avgprice') ? $errors->first('avgprice') : '') }}
                    </div>

                    <div class="form-group {{ ($errors->has('minprice')) ? 'has-error' : '' }}" for="maxprice">
                        {{ Form::label('maxprice', 'Max. price:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-2">
                            {{ Form::text('maxprice', null, array('class' => 'form-control', 'placeholder' => 'Max. price')) }}
                        </div>
                        {{ ($errors->has('maxprice') ? $errors->first('maxprice') : '') }}
                    </div>

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
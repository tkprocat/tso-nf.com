@extends('layouts.default-admin')

{{-- Web site Title --}}
@section('title')
@parent
UpdatePrice
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
                    <li><a href="/admin/prices">Prices</a></li>
                    <li><a href="/admin/prices/create">- Add new item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h4>Update price for {{ $item->name }}</h4>
                {{ HTML::ul($errors->all()) }}
                <div class="well">
                    {{ Form::open(array('action' => 'AdminPriceListController@storeNewPrice', 'class' => "form-horizontal")) }}
                    {{ Form::hidden('item_id', $item->id) }}

                    <div class="form-group {{ ($errors->has('min_price')) ? 'has-error' : '' }}" for="min_price">
                        {{ Form::label('min_price', 'Min. price:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-2">
                            {{ Form::text('min_price', null, array('class' => 'form-control', 'placeholder' => 'Min. price')) }}
                        </div>
                        {{ ($errors->has('min_price') ? $errors->first('min_price') : '') }}
                    </div>

                    <div class="form-group {{ ($errors->has('avg_price')) ? 'has-error' : '' }}" for="avg_price">
                        {{ Form::label('avg_price', 'Avg. price:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-2">
                            {{ Form::text('avg_price', null, array('class' => 'form-control', 'placeholder' => 'Avg. price')) }}
                        </div>
                        {{ ($errors->has('avg_price') ? $errors->first('avg_price') : '') }}
                    </div>

                    <div class="form-group {{ ($errors->has('max_price')) ? 'has-error' : '' }}" for="max_price">
                        {{ Form::label('max_price', 'Max. price:', array('class' =>  'col-sm-2 control-label')) }}
                        <div div class="col-sm-2">
                            {{ Form::text('max_price', null, array('class' => 'form-control', 'placeholder' => 'Max. price')) }}
                        </div>
                        {{ ($errors->has('max_price') ? $errors->first('max_price') : '') }}
                    </div>

                    {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
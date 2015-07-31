@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Add adventures'))
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Adding new adventure</div>
                        <div class="panel-body">
                            @include('errors.list')
                            <form method="POST" action="/admin/adventures" accept-charset="UTF-8" class="form-horizontal">
                                {!! csrf_field() !!}

                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="name" class="form-control" placeholder="Adventure name" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="type" class="col-sm-2 control-label">Type:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="type" class="form-control" placeholder="Adventure type" value="{{ old('type') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="disabled" class="col-sm-2 control-label">Disabled:</label>
                                    <div class="col-sm-1">
                                        <input type="checkbox" name="disabled" class="checkbox-inline" value="{{ old('disabled') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-1">Slot</div>
                                    <div class="col-sm-4">Item name</div>
                                    <div class="col-sm-2">Amount</div>
                                </div>

                                @for ($i = 1; $i < 50; $i++)
                                <div class="form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-1">
                                        <input type="text" class="form-control" placeholder="Slot" name="items[{{$i}}][slot]" value="{{ old('items[$i][slot]') }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="items[{{$i}}][itemid]" class="form-control">
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" {!! old('items[$i][itemid]') == $item->id ? 'selected="selected"' : ''  !!}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" placeholder="Amount" name="items[{{$i}}][amount]" value="{{ old('items[$i][amount]') }}">
                                    </div>
                                </div>
                                @endfor
                                <input type="submit" value="Add adventure" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
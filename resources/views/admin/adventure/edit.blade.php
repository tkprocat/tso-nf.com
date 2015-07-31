@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Add adventures'))
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Updating adventure: {{ $adventure->name }}</div>
                        <div class="panel-body">
                            @include('errors.list')
                            <form method="POST" action="/admin/adventures/{{ $adventure->id }}" accept-charset="UTF-8" class="form-horizontal">
                                <input type="hidden" name="_method" value="PUT">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name:</label>
                                    <div class="col-sm-10">
                                        <input name="name" class="form-control" value="{{ old('name', $adventure->name) }}" placeholder="Adventure name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="type" class="col-sm-2 control-label">Type:</label>
                                    <div class="col-sm-10">
                                        <input name="type" class="form-control" value="{{ old('type', $adventure->type) }}" placeholder="Adventure type">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="disabled" class="col-sm-2 control-label">Disabled:</label>
                                    <div class="col-sm-10">
                                        <input name="disabled" type="checkbox" value="{{ old('disabled', $adventure->disabled) }}" class="checkbox-inline">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-1">Slot</div>
                                    <div class="col-sm-4">Item name</div>
                                    <div class="col-sm-2">Amount</div>
                                </div>

                                <div class="form-group ">
                                    @for ($i = 1; $i <= count($adventure->loot); $i++)
                                        <div class="form-group ">
                                            <div class="col-sm-2">
                                                <input type="hidden" name="items[{{$i}}][id]" value="{{old('items[$i][id]', $adventure->loot[$i-1]->id)}}">
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="text" name="items[{{$i}}][slot]" class="form-control" value="{{ old('items[$i][slot]', $adventure->loot[$i-1]->slot) }}" placeholder="Slot">
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="items[{{$i}}][itemid]" class="form-control">
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" {!! old('items[$i][itemid]', $adventure->loot[$i-1]->item_id) == $item->id ? 'selected="selected"' : ''  !!}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="items[{{$i}}][amount]" class="form-control" value="{{ old('items[$i][amount]', $adventure->loot[$i-1]->amount) }}" placeholder="Amount">
                                            </div>
                                        </div>
                                    @endfor
                                    @for ($i = count($adventure->loot)+1; $i < count($adventure->loot)+6; $i++)
                                        <div class="form-group ">
                                            <div class="col-sm-2">

                                            </div>
                                            <div class="col-sm-1">
                                                <input type="text" name="items[{{$i}}][slot]" class="form-control" value="{{ old('items[$i][slot]') }}" placeholder="Slot">
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="items[{{$i}}][itemid]" class="form-control">
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" {!! old('items[$i][itemid]') == $item->id ? 'selected="selected"' : ''  !!}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="items[{{$i}}][amount]" class="form-control" value="{{ old('items[$i][amount]') }}" placeholder="Amount">
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <input type="submit" value="Update" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
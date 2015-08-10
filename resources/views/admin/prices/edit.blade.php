@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Prices'))
<div class="panel panel-default">
    <div class="panel-heading">Update prices for {{ $item->name }}</div>
    <div class="panel-body">
        @include('errors.list')
        <form method="POST" action="/admin/prices/{{ $item->id }}" accept-charset="UTF-8" class="form-horizontal">
            {!! csrf_field() !!}
            <input name="_method" type="hidden" value="PUT">

            <div class="form-group">
                <label for="min_price" class="col-sm-2 control-label">Min. price:</label>
                <div class="col-sm-2">
                    <input type="text" name="min_price" class="form-control" placeholder="Min. price" value="{{ old('min_price', $item->currentPrice->min_price) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="avg_price" class="col-sm-2 control-label">Avg. price:</label>
                <div class="col-sm-2">
                    <input type="text" name="avg_price" class="form-control" placeholder="Avg. price" value="{{ old('avg_price', $item->currentPrice->avg_price) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="max_price" class="col-sm-2 control-label">Max. price:</label>
                <div class="col-sm-2">
                    <input type="text" name="max_price" class="form-control" placeholder="Max. price" value="{{ old('max_price', $item->currentPrice->max_price) }}">
                </div>
            </div>

            <div class="col-md-offset-6">
                <input type="submit" value="Update" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@stop
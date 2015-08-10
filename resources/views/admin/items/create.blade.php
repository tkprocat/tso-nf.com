@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Add prices'))
<div class="panel panel-info">
    <div class="panel-heading">Add item</div>
    <div class="panel-body">
        @include('errors.list')
        <form method="POST" action="/admin/items" accept-charset="UTF-8" class="form-horizontal">
            {!! csrf_field() !!}

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name:</label>
                <div class="col-sm-4">
                    <input type="text" name="name" class="form-control" placeholder="Item name" value="{{ old('name') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="category" class="col-sm-2 control-label">Category:</label>
                <div class="col-sm-4">
                    <input type="text" name="category" class="form-control" placeholder="Category" value="{{ old('category') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="tradable" class="col-sm-2 control-label">Tradable:</label>
                <div class="col-sm-4">
                    <input type="checkbox" name="tradable" class="checkbox" {{ old('tradable', 'checked') ? 'checked' : ''}}>
                </div>
            </div>

            <div class="form-group">
                <label for="stackable" class="col-sm-2 control-label">Stackable:</label>
                <div class="col-sm-4">
                    <input type="checkbox" name="stackable" class="checkbox" {{ old('stackable', 'checked') ? 'checked' : ''}}>
                </div>
            </div>

            <div class="form-group">
                <label for="min_price" class="col-sm-2 control-label">Min. price:</label>
                <div class="col-sm-2">
                    <input type="text" name="min_price" class="form-control" placeholder="Min. price" value="{{ old('min_price') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="avg_price" class="col-sm-2 control-label">Avg. price:</label>
                <div class="col-sm-2">
                    <input type="text" name="avg_price" class="form-control" placeholder="Avg. price" value="{{ old('avg_price') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="max_price" class="col-sm-2 control-label">Max. price:</label>
                <div class="col-sm-2">
                    <input type="text" name="max_price" class="form-control" placeholder="Max. price" value="{{ old('max_price') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-5 col-md-2">
                    <input type="submit" value="Add" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
</div>
@stop
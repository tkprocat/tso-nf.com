@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Items'))
<div class="panel panel-default">
    <div class="panel-heading">Update item</div>
    <div class="panel-body">
        @include('layouts/notifications')
        <form method="POST" action="/admin/items/{{ $item->id }}" accept-charset="UTF-8" class="form-horizontal">
            {!! csrf_field() !!}
            {!! method_field('put') !!}

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name:</label>
                <div class="col-sm-4">
                    <input type="text" name="name" class="form-control" placeholder="Item name" value="{{ old('name', $item->name) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Category:</label>
                <div class="col-sm-4">
                    <input type="text" name="category" class="form-control" placeholder="Category" value="{{ old('category', $item->category) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Tradable:</label>
                <div class="col-sm-4">
                    <input type="checkbox" name="tradable" {{ old('tradable', $item->tradable) ? 'checked' : ''}}>
                </div>
            </div>

            <div class="form-group">
                <label for="stackable" class="col-sm-2 control-label">Stackable:</label>
                <div class="col-sm-4">
                    <input type="checkbox" name="stackable" {{ old('stackable', $item->stackable) ? 'checked' : ''}}>
                </div>
            </div>

            <input type="submit" value="Update" class="btn btn-warning">
        </form>
    </div>
</div>
@stop
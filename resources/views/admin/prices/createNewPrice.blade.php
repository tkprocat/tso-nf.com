@extends('layouts.default-admin')
@section('content')
@include('admin.menu', array('active' => 'Add prices'))
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header">Add new item</h1>
    <div class="well">
        @include('errors.list')
        <form method="POST" action="/admin/prices/newprice/{{ $item->id }}" accept-charset="UTF-8" class="form-horizontal">
            {!! csrf_field() !!}

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
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>
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
@stop
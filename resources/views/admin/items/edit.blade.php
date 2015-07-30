@extends('layouts.default-admin')
@section('content')
    @include('admin.menu', array('active' => 'Prices'))
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Add new item</h1>
        <div class="well">
            @include('errors.list')
            <form method="POST" action="/admin/prices/{{ $item->id }}" accept-charset="UTF-8" class="form-horizontal">
                {!! csrf_field() !!}

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name:</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" placeholder="Item name" value="{{ old('name', $item->name) }}">
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
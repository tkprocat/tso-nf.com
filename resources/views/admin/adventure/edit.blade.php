@extends('layouts.default-admin')

{{-- Web site Title --}}
@section('title')
    @parent
    Update adventure
@stop

{{-- Content --}}
@section('content')
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li><a href="/admin/">Overview</a></li>
                    <li><a href="/admin/users">Users</a></li>
                    <li class="active"><a href="/admin/adventures">Adventures <span class="sr-only">(current)</span></a></li>
                    <li><a href="/admin/adventures/create">- Add new adventure</a></li>
                    <li><a href="/admin/prices">Prices</a></li>
                    <li><a href="/admin/prices/create">- Add new item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h4>Update adventure:</h4>

                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
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
                                                <input type="text" name="items[{{$i}}][type]" class="form-control" value="{{ old('items[$i][type]', $adventure->loot[$i-1]->type) }}" placeholder="Item">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="items[{{$i}}][amount]" class="form-control" value="{{ old('items[$i][amount]', $adventure->loot[$i-1]->slot) }}" placeholder="Amount">
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
                                                <input type="text" name="items[{{$i}}][type]" class="form-control" value="{{ old('items[$i][type]') }}" placeholder="Item">
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

    <script>
        var items = [];
        var adventureTypes = [];
        $(document).ready(function () {
            $.get("/admin/adventures/getItemTypes", function (data) {
                items = data;
            });
            $.get("/admin/adventures/getAdventureTypes", function (data) {
                adventureTypes = data;
            });
        });

        $(".items").autocomplete({
            source: function (request, response) {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(items, function (item) {
                    return matcher.test(item);
                }));
            }
        });

        $("#type").autocomplete({
            source: function (request, response) {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(adventureTypes, function (item) {
                    return matcher.test(item);
                }));
            }
        });
    </script>
@stop
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
                    <li class="active"><a href="/admin/prices">Prices <span class="sr-only">(current)</span></a></li>
                    <li><a href="/admin/prices/create">- Add new item</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h4>Update adventure:</h4>

                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                            {{ Form::open(array('action' => array('AdminPriceListController@update', $item->id), 'method' => 'put', 'class' => "form-horizontal")) }}
                            {{ Form::hidden("item_id", $item->id) }}
                            <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                                {{ Form::label('name', 'Name:', array('class' =>  'col-sm-2 control-label')) }}
                                <div class="col-sm-10">
                                    {{ Form::text('name', $item->name, array('class' => 'form-control', 'placeholder' => 'Adventure name')) }}
                                </div>
                                {{ ($errors->has('name') ? $errors->first('name') : '') }}
                            </div>

                            <div class="form-group ">

                            </div>
                            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var items = [];
        $(document).ready(function () {
            $.get("/admin/adventures/getItemTypes", function (data) {
                items = data;
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
    </script>
@stop
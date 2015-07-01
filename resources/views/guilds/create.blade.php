@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Register
@stop

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create new guild</div>
                <div class="panel-body">
                    <form method="POST" action="/guild" accept-charset="UTF-8" class="form-horizontal">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="name">Guild name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Guild name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="tag">Guild tag</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="tag" value="{{ old('tag') }}" placeholder="Guild tag">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
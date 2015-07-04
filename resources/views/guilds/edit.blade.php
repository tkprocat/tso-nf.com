@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Edit Profile
@stop

{{-- Content --}}
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit guild</div>
                <div class="panel-body">
                    <form method="POST" action="/guilds/{{ $guild->id }}" accept-charset="UTF-8" class="form-horizontal" role="form">
                        <input name="_method" type="hidden" value="PUT">
                        {!! csrf_field() !!}

                        @include('errors.list')

                        <div class="form-group" for="name">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="{{ old('name', $guild->name) }}" class="form-control" placeholder="Guild name">
                            </div>
                        </div>

                        <div class="form-group" for="tag">
                            <label for="tag" class="col-sm-2 control-label">Tag</label>
                            <div class="col-sm-2">
                                <input type="text" name="tag" value="{{ old('tag', $guild->tag) }}" class="form-control" placeholder="Guild tag">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-primary" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
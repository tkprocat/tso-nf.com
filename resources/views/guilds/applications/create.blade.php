@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Application to join guild {{ $guild->name }}</div>
                <div class="panel-body">
                    @include('errors.list')
                    <form method="POST" action="/guilds/{{ $guild->id }}/applications" accept-charset="UTF-8" class="form-horizontal">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-2 control-label" for="message">Message</label>
                            <div class="col-md-10">
                                <textarea name="message" class="form-control" placeholder="Write a short message to the guild admins.">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-5">
                                <button type="submit" class="btn btn-primary">
                                    Apply
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
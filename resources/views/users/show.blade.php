@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $user->username }}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <span class="col-md-3">Guild:</span>
                            <div class="col-md-9">
                                {{ (isset($user->guild) ? $user->guild->name : 'Not in a guild.')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
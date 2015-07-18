@extends('layouts.default')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Application to join guild {{ $guild->name }}</div>
                    <div class="panel-body">
                        @foreach ($guildApplications as $application)
                            <div class="well">
                                {{ $application->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
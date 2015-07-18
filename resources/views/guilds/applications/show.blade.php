@extends('layouts.default')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Application to join guild from
                        <strong>
                            <a href="/stats/personal/{{ $application->user->username }}" >{{ $application->user->username }}</a>
                        </strong>
                    </div>
                    <div class="panel-body">
                        <p>Message from the user:</p>
                        <p>{{ $application->message }}</p>

                        <div class="col-md-offset-4">
                            <a href="/guilds/{{ $application->guild->id }}/applications/{{ $application->id }}/approve" class="btn btn-success">Approve</a>
                            <a href="/guilds/{{ $application->guild->id }}/applications/{{ $application->id }}/decline" class="btn btn-danger">Decline</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
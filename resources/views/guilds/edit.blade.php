@extends('layouts.default')
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

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Applications</div>
                <div class="panel-body">
                    <ul>
                    @foreach($applications as $application)
                        <li><a href="/guilds/{{$guild->id}}/applications/{{$application->id}}">{{$application->user->username}}</a></li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add member</div>
                <div class="panel-body">
                    <p class="text-info">Type in a name of an user that currently isn't in a guild and hit add.</p>
                    <form method="POST" action="/guilds/{{ $guild->id }}/add" accept-charset="UTF-8" class="form-horizontal">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input type="hidden" name="guild_id" value="{{ $guild->id }}">

                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">Username:</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" class="form-control" placeholder="Username">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Add" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Disband guild</div>
                <div class="panel-body">
                    <p class="text-danger">WARNING: Disbanding a guild will kick all members and delete the guild from our systems, use with care!</p>
                    <a href="/guilds/{{ $guild->id }}" data-method="delete" rel="nofollow" class="btn btn-danger" data-confirm="Are you sure you want to disband the guild?">Disband guild</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
@parent
Edit Profile
@stop

{{-- Content --}}
@section('content')

<h4>Edit
    @if ($user->email == Sentry::getUser()->email)
    Your
    @else
    {{ $user->email }}'s
    @endif

    Profile</h4>
<div class="well">
    {{ Form::open(array(
    'action' => array('UserController@update', $user->id),
    'method' => 'put',
    'class' => 'form-horizontal',
    'role' => 'form'
    )) }}


    @if (Sentry::getUser()->hasAccess('admin'))
    <div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}" for="username">
        {{ Form::label('edit_username', 'Username', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('username', $user->username, array('class' => 'form-control', 'placeholder' => 'Username',
            'id' => 'edit_username'))}}
        </div>
        {{ ($errors->has('username') ? $errors->first('username') : '') }}
    </div>
    @endif

    @if (Sentry::getUser()->hasAccess('admin'))
    <div class="form-group">
        {{ Form::label('edit_memberships', 'Group Memberships', array('class' => 'col-sm-2 control-label'))}}
        <div class="col-sm-10">
            @foreach ($allGroups as $group)
            <label class="checkbox-inline">
                <input type="checkbox" name="groups[{{ $group->id }}]" value='1'
                {{ (in_array($group->name, $userGroups) ? 'checked="checked"' : '') }} > {{ $group->name }}
            </label>
            @endforeach
        </div>
    </div>
    @endif

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::hidden('id', $user->id) }}
            {{ Form::submit('Submit Changes', array('class' => 'btn btn-primary'))}}
        </div>
    </div>
    {{ Form::close()}}
</div>

<h4>Change Password</h4>
<div class="well">
    {{ Form::open(array(
    'action' => array('UserController@change', $user->id),
    'class' => 'form-inline',
    'role' => 'form'
    )) }}

    <div class="form-group {{ $errors->has('oldPassword') ? 'has-error' : '' }}">
        {{ Form::label('oldPassword', 'Old Password', array('class' => 'sr-only')) }}
        {{ Form::password('oldPassword', array('class' => 'form-control', 'placeholder' => 'Old Password')) }}
    </div>

    <div class="form-group {{ $errors->has('newPassword') ? 'has-error' : '' }}">
        {{ Form::label('newPassword', 'New Password', array('class' => 'sr-only')) }}
        {{ Form::password('newPassword', array('class' => 'form-control', 'placeholder' => 'New Password')) }}
    </div>

    <div class="form-group {{ $errors->has('newPassword_confirmation') ? 'has-error' : '' }}">
        {{ Form::label('newPassword_confirmation', 'Confirm New Password', array('class' => 'sr-only')) }}
        {{ Form::password('newPassword_confirmation', array('class' => 'form-control', 'placeholder' => 'Confirm New
        Password')) }}
    </div>

    {{ Form::submit('Change Password', array('class' => 'btn btn-primary'))}}

    {{ ($errors->has('oldPassword') ? '<br/>' . $errors->first('oldPassword') : '') }}
    {{ ($errors->has('newPassword') ? '<br/>' . $errors->first('newPassword') : '') }}
    {{ ($errors->has('newPassword_confirmation') ? '<br/>' . $errors->first('newPassword_confirmation') : '') }}

    {{ Form::close() }}
</div>

<h4>Theme</h4>
<div class="well">
    {{ Form::open(array(
    'method' => 'put',
    'class' => 'form-horizontal',
    'role' => 'form'
    )) }}
    <div class="form-group" for="theme">
        {{ Form::label('theme', 'Theme', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::select('theme', array('amelia' => 'Amelia', 'cosmo' => 'Cosmo', 'readable' => 'Readable', 'slate'
            => 'Slate'), null, array('class' => 'form-control', 'id' => 'theme')) }}
        </div>
    </div>

    {{ Form::close() }}
</div>

<script>
    $('#theme').on('change', function () {
        $.cookie('theme', $('#theme').val(), {path: '/', expires: 365});

        if ($('#theme').val() == 'amelia') {
            $("<link/>", {
                rel: "stylesheet",
                type: "text/css",
                href: "{{ URL::to('/') }}/assets/css/bootstrap-amelia.css"
            }).appendTo("head");
        } else if ($('#theme').val() == 'cosmo') {
            $("<link/>", {
                rel: "stylesheet",
                type: "text/css",
                href: "{{ URL::to('/') }}/assets/css/bootstrap-cosmo.css"
            }).appendTo("head");
        } else if ($('#theme').val() == 'readable') {
            $("<link/>", {
                rel: "stylesheet",
                type: "text/css",
                href: "{{ URL::to('/') }}/assets/css/bootstrap-readable.css"
            }).appendTo("head");
        } else {
            $("<link/>", {
                rel: "stylesheet",
                type: "text/css",
                href: "{{ URL::to('/') }}/assets/css/bootstrap.css"
            }).appendTo("head");
        }
    });
</script>
@stop
@extends('layouts.default-admin')
@include('admin.menu', array('active' => 'Users'))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Account Profile</div>
    <div class="panel-body">
        @include('errors.list')

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/users/'. $user->id)}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="form-group">
                <label for="username" class="col-md-2">Username:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-md-2">Email:</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="email" value="{{ old('email', $user->email) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="activated" class="col-md-2">Activated:</label>
                <div class="col-md-9">
                    <input type="checkbox" class="checkbox" name="activated" {{ old('activated', $user->activated) ? 'checked' : '' }}>
                </div>
            </div>

            <div class="form-group">
                <label for="prices_admin" class="col-md-2">Price admin:</label>
                <div class="col-md-9">
                    <input type="checkbox" class="checkbox" name="prices_admin" {{ old('prices_admin', $user->hasRole('prices_admin')) ? 'checked' : '' }}>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-md-offset-5">
                    <input type="submit" value="Update" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Change password</div>
    <div class="panel-body">
        @include('errors.list')

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/users/'. $user->id.'/password/change') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="password" class="col-md-2">New password:</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="password">
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="col-md-2">Confirm new password:</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-md-offset-5">
                    <input type="submit" value="Change password" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
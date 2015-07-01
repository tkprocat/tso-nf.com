@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Change password</div>
                    <div class="panel-body">
                        @include('errors.list')
                        <form method="POST" action="{{ url('/password/change') }}" class="form-inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password">
                                <input type="password" class="form-control" name="password_confirmation">
                                <input type="submit" value="Change Password" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
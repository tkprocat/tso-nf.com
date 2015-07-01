@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Change password</div>
                    <div class="panel-body">
                        @include('errors.list')

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/change') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label for="password_old" class="col-md-3">Old password:</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="password_old">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-3">New password:</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="col-md-3">Confirm new password:</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <input type="submit" value="Change Password" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Activate account</div>

                    <div class="panel-body">
                        <p>{{ trans('auth.sentEmail', ['email' => $email] ) }}</p>

                        <p>{{ trans('auth.clickInEmail') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

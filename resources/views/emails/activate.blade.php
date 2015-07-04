<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{ trans('auth.welcome') }}</h2>

<p><br>{{ trans('auth.welcome2') }}</p>

<p><b>{{ trans('auth.account') }}:</b> {{{ $username }}}</p>
<p>{{ trans('auth.toActivate') }} <a href="{{ url('auth/activate/'.$code) }}">{{ trans('auth.clickHere') }}.</a></p>
<p>{{ trans('auth.toActivate2') }} <br /> <a href="{{ url('auth/activate/'.$code) }}">{{ url('auth/activate/'.$code) }}</a></p>
<p>{{ trans('auth.thankyou') }}<br />{{ trans('auth.admins') }}</p>
</body>
</html>
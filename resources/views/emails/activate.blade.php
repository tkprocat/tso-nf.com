<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{ trans('auth.welcome') }}</h2>

<p><b>{{ trans('auth.toActivate') }}:</b> {{{ $username }}}</p>
<p>{{ trans('auth.toActivate') }} <a href="{{ url('auth/activate/'.$code) }}">{{ trans('clickHere') }}.</a></p>
<p>{{ trans('auth.toActivate2') }} <br /> {{ url('auth/activate/'.$code) }}</p>
<p>{{ trans('auth.thankyou') }}<br />{{ trans('auth.admins') }}</p>
</body>
</html>
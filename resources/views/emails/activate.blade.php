<h2>{{ trans('auth.welcome') }}</h2><br>
<br>
<strong>{{ trans('auth.account') }}: {{ $username }}</strong><br>
<br>
{{ trans('auth.toActivate') }}<a href="{{ url('auth/activate/'.$code) }}" >{{ trans('clickHereActivate') }}</a><br>
<br>
{{ trans('auth.toActivate2') }}<a href="{{ url('auth/activate/'.$code) }}" >{{ url('auth/activate/'.$code) }}</a><br>
<br>
<br>
{{ trans('auth.thankyou') }}<br>
{{ trans('auth.admins') }}

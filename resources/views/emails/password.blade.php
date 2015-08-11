{{ Lang::get('auth.clickHereReset') }}
<a href="{{ url('password/reset/'.$token) }}" >
    {{ url('password/reset/'.$token) }}
</a>
<p>{{ Lang::get('auth.thankyou') }}<br>
{{ Lang::get('auth.admins') }}</p>
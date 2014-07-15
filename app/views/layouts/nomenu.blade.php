<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lazy Monkeys Loot Tracker</title>
    @if((isset($_COOKIE['theme'])) && ($_COOKIE['theme'] == 'amelia'))
    {{ HTML::style('assets/css/bootstrap-amelia.css') }}
    @elseif((isset($_COOKIE['theme'])) && ($_COOKIE['theme'] == 'cosmo'))
    {{ HTML::style('assets/css/bootstrap-cosmo.css') }}
    @elseif((isset($_COOKIE['theme'])) && ($_COOKIE['theme'] == 'readable'))
    {{ HTML::style('assets/css/bootstrap-readable.css') }}
    @else
    {{ HTML::style('assets/css/bootstrap.css') }}
    @endif
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/jquery.validate.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/bootstrap.js"></script>
</head>
<body>

<div class="container">
    @include('layouts/notifications')

    @yield('content')
</div>


</body>
</html>

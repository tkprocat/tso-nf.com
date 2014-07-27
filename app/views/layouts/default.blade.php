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

    {{ HTML::style('/assets/bower/bootstrap-sortable/Contents/bootstrap-sortable.css') }}
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/jquery.validate.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="{{ URL::to('/') }}/assets/js/bootstrap-sortable.js"></script>
</head>
<body>

@if (App::environment() == 'staging')
<div class="alert alert-warning" role="alert">This is a testing site, pleas don't register your actually loot here and expect it to stick!</div>
@endif

<div class="container">
    @if ((!isset($popup_mode)) || (isset($popup_mode) && !$popup_mode))
    <div class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a href="{{ URL::to('/blog') }}" class="navbar-brand">LM Loot Tracker</a>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse navbar-responsive-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{ URL::to('blog') }}">Blog</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('stats/global') }}">Global stats</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('loot') }}">Latest</a>
                    </li>
                    @if (Sentry::check())
                    <?php $currentUser = Sentry::getUser(); ?>
                    <li>
                        <a href="{{ URL::to('loot/create') }}">Add loot</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('stats/personal') }}">Personal stats</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('labs') }}">Labs</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('users') }}">Users</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('guilds') }}">Guilds</a>
                    </li>
                    <li {{ (Request::is('users/' . $currentUser->id) ? 'class="active"' : '') }}>
                        <a href="{{ URL::to('users') .'/'. $currentUser->id }}">{{ $currentUser->username }}</a>
                    </li>
                    <li>
                        <a href="{{ URL::to('logout') }}">Logout</a>
                    </li>
                    @else
                    <li {{ (Request::is('login') ? 'class="active"' : '') }}>
                        <a href="{{ URL::to('login') }}">Login</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif
    @include('layouts/notifications')

    @yield('content')
</div>
</body>
</html>

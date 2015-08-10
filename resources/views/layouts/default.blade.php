<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="csrf-param" content="_token" />
        <title>Loot Tracker</title>
        <link rel="stylesheet" href="/css/all.css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="/js/all.js"></script>
    </head>
    <body>
        <div class="container">
                <div class="navbar navbar-inverse">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="{{ URL::to('/blog') }}" class="navbar-brand">Loot Tracker</a>
                            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="navbar-collapse collapse navbar-responsive-collapse">
                            <ul class="nav navbar-nav">
                                <li {!! (Request::segment(1)==='blog' ? 'class="active"' : '') !!}>
                                <a href="{{ URL::to('blog') }}">Blog</a>
                                </li>
                                @if (Auth::check())
                                    <li {!! ((Request::segment(1)=== 'stats') ? 'class="dropdown active"' : 'class="dropdown"') !!}>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Stats<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown">Global</a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ URL::to('stats/global') }}">Overall</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to('stats/global/top10bydrop') }}">Top 10 (Drop)</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to('stats/global/submissionrate') }}">Submission rate</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to('stats/global/newuserrate') }}">Signup rate</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            @if(Auth::user()->guild_id > 0)
                                                <li class="dropdown-submenu">
                                                    <a tabindex="0" data-toggle="dropdown">Guild</a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="{{ URL::to('stats/guild') }}">Overall</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ URL::to('stats/guild/submissionrate') }}">Submission rate</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ URL::to('stats/personal') }}">Personal</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li {!! ((Request::segment(1)=== 'loot') ? 'class="dropdown active"' : 'class="dropdown"') !!}>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Loot<span
                                                    class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ URL::to('loot/create') }}">Add loot</a>
                                            </li>
                                            <li>
                                                <a href="{{ URL::to('loot') }}">Latest</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li {!! ((Request::segment(1)=== 'prices') ? 'class="dropdown active"' : 'class="dropdown"') !!}>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Prices<span
                                                    class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ URL::to('prices') }}">Price list</a>
                                            </li>
                                            <li>
                                                <a href="{{ URL::to('prices/simplecalc') }}">Simple Calc</a>
                                            </li>
                                            <li>
                                                <a href="{{ URL::to('prices/advancedcalc') }}">Advanced Calc</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li {!! (Request::is('users') ? 'class="active"' : '') !!}>
                                        <a href="{{ URL::to('users') }}">Users</a>
                                    </li>
                                    <li {!! ((Request::segment(1) === 'guilds') ? 'class="active"' : '')  !!}>
                                        <a href="{{ URL::to('guilds') }}">Guilds</a>
                                    </li>
                                    @if (Auth::user()->guild_id > 0)
                                    <li {!! ((Request::segment(1) . '/'. Request::segment(2) === 'guilds/'.Auth::user()->guild_id) ? 'class="dropdown active"' : 'class="dropdown"') !!}>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->guild->tag }}<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ URL::to('guilds/'.Auth::user()->guild_id) }}">My guild</a>
                                            </li>
                                            @if (Auth::user()->can('admin-guild'))
                                            <li>
                                                <a href="{{ URL::to('guilds/'.Auth::user()->guild_id.'/edit') }}">Settings</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    @endif
                                    <li {!! (Request::is('users/' . Auth::user()->username) ? 'class="active"' : '') !!}>
                                        <a href="{{ URL::to('users') .'/'. Auth::user()->username .'/edit'}}">{{ Auth::user()->username }}</a>
                                    </li>
                                    @if (Entrust::hasRole('admin') || Entrust::hasRole('prices_admin'))
                                        <li>
                                            <a href="{{ URL::to('admin/') }}">Admin</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ URL::to('auth/logout') }}">Logout</a>
                                    </li>
                                @else
                                    <li {!! (Request::is('auth/login') ? 'class="active"' : '') !!}>
                                        <a href="{{ URL::to('auth/login') }}">Login</a>
                                    </li>
                                    <li {!! (Request::is('auth/register') ? 'class="active"' : '') !!}>
                                        <a href="{{ URL::to('auth/register') }}">Sign up</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <script>
                    $('.dropdown-submenu > a').submenupicker();
                </script>
            @include('layouts/notifications')

            @yield('content')
        </div>
    </body>
</html>

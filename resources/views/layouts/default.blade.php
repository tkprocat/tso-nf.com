﻿<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Loot Tracker</title>
    <link rel="stylesheet" href="/css/all.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/ui-darkness/jquery-ui.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
@if (App::environment() == 'staging')
    <div class="alert alert-warning" role="alert">This is a testing site, pleas don't register your actually loot here
        and
        expect it to stick!
    </div>
@endif
<div class="container">
    @if ((!isset($popup_mode)) || (isset($popup_mode) && !$popup_mode))
        <div class="navbar navbar-default">
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
                        <li {!! (Request::segment(1)===
                        'blog' ? 'class="active"' : '') !!}>
                        <a href="{{ URL::to('blog') }}">Blog</a>
                        </li>
                        @if (Auth::check())
                            <li {!! ((Request::segment(1)=== 'stats') ? 'class="dropdown active"' : 'class="dropdown"')
                            !!}>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Stats<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ URL::to('stats/global') }}">Global</a>
                                </li>
                                <li>
                                    <a href="{{ URL::to('stats/personal') }}">Personal</a>
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
                            <li {!! ((Request::segment(1)=== 'loot') ? 'class="dropdown active"' : 'class="dropdown"')
                            !!}>
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
                            <li {{ (Request::is('users') ? 'class="active"' : '') }}>
                                <a href="{{ URL::to('users') }}">Users</a>
                            </li>
                            <li {{ ((Request::segment(1) === 'guilds') ? 'class="active"' : '') }}>
                                <a href="{{ URL::to('guilds') }}">Guilds</a>
                            </li>
                            <li {{ (Request::is('users/' . Auth::user()->username) ? 'class="active"' : '') }}>
                                <a href="{{ URL::to('users') .'/'. Auth::user()->username .'/edit'}}">{{ Auth::user()->username }}</a>
                            </li>
                            @if (Entrust::hasRole('admin'))
                                <li>
                                    <a href="{{ URL::to('admin/') }}">Admin</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ URL::to('auth/logout') }}">Logout</a>
                            </li>
                        @else
                            <li {{ (Request::is('auth/login') ? 'class="active"' : '') }}>
                                <a href="{{ URL::to('auth/login') }}">Login</a>
                            </li>
                            <li {{ (Request::is('auth/register') ? 'class="active"' : '') }}>
                                <a href="{{ URL::to('auth/register') }}">Sign up</a>
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

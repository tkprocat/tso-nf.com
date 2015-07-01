<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>tso-nf.com Loot Tracker</title>
    <link rel="stylesheet" href="/css/all.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/ui-darkness/jquery-ui.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">tso-nf.com</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <!--  <li><a href="#">Dashboard</a></li>
                  <li><a href="#">Profile</a></li> -->
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        @yield('content')
    </div>
</div>
</body>
</html>

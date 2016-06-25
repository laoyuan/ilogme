<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ilogme</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir('assets/css/all.css') }}">
    @yield('css')

</head>

<body id="app-layout">

    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    iLogme
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/u') }}">用户列表</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">登录</a></li>
                        <li><a href="{{ url('/register') }}">注册</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/u/settings') }}"><i class="fa fa-btn fa-sign-out"></i>设置</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>退出</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        
        @yield('content')

    </div>


    <footer class="footer">
        <div class="container">
            <div class="footer-con">
                <p>Made by <a href="https://github.com/laoyuan/ilogme">Laoyuan</a>, based on <a href="https://laravel.com/">Laravel</a> & <a href="http://getbootstrap.com" rel="nofollow">Bootstrap</a>.</p>
            </div>
        </div>
    </footer>


    <!-- JavaScripts -->
    <script type="text/javascript" src="{{ elixir('assets/js/all.js') }}"></script>
    @yield('js')

</body>
</html>

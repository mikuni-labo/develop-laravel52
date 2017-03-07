@section('gnavi')
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
            
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <!-- Branding Image -->
                <a class="navbar-brand" href="/">{{ $Fixed['info']['SiteName'] }}</a>
            </div>
            
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if (Auth::guard('user')->check())
                        @if (Auth::guard('user')->user()->role === 'ADMINISTRATOR')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span>&nbsp;ユーザ管理&nbsp;<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="/user"><span class="glyphicon glyphicon-user"></span>&nbsp;ユーザ一覧</a></li>
                                    <li><a href="/user/add"><span class="glyphicon glyphicon-plus"></span>&nbsp;ユーザ登録</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-link"></span>&nbsp;テスト&nbsp;<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="/test"><span class="glyphicon glyphicon-user"></span>&nbsp;テスト一覧</a></li>
                                    @if(false)<li><a href="/test/add"><span class="glyphicon glyphicon-plus"></span>&nbsp;テスト登録</a></li>@endif
                                </ul>
                            </li>
                        @endif
                    @endif
                </ul>
                
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li class="dropdown">
                        @if (Auth::guard('user')->check())
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-cog"></span>&nbsp;{{ Auth::guard('user')->user()->name1 }}&nbsp;{{ Auth::guard('user')->user()->name2 }}&nbsp;さん
                                （{{ $Fixed['role'][Auth::guard('user')->user()->role] }}）<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/"><span class="glyphicon glyphicon-home"></span>&nbsp;ホーム</a></li>
                                <li><a href="/user/edit/{{ Auth::guard('user')->user()->id }}"><span class="glyphicon glyphicon-edit"></span>&nbsp;アカウント情報編集</a></li>
                                <li><a href="/auth/logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;ログアウト</a></li>
                            </ul>
                        @else
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                GUEST <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/auth/login"><span class="glyphicon glyphicon-log-in"></span>&nbsp;ログイン</a></li>
                            </ul>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

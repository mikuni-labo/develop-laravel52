@extends('layouts.base')

@section('meta')
    <title>ログイン | {{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-4 col-md-offset-1">
                    <ul class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">Login</li>
                    </ul>
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-10 col-md-offset-1">
                    
                    @include('flash::message')
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">ログイン</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="/auth/login">
                                {!! csrf_field() !!}
                                
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Eメールアドレス</label>
                                    
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" required="required" value="{{ old('email') }}">
                                        
                                        @if ($errors->has('email'))
                                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">パスワード</label>
                                    
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" name="password" required="required">
                                        
                                        @if ($errors->has('password'))
                                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-log-in"></span>&nbsp;ログイン
                                        </button>
                                        
                                        <a class="btn btn-link" href="/auth/password/email"><span class="glyphicon glyphicon-check"></span>&nbsp;パスワードをお忘れの方はこちら</a>
                                        @if(false)<a class="btn btn-link" href="/auth/resend"><span class="glyphicon glyphicon-check"></span>&nbsp;ユーザー登録確認メールを再送する</a>@endif
                                    </div>
                                </div>
                            </form>
                        </div><!-- .panel-body -->
                    </div><!-- .panel -->
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
@endsection

@include('layouts.footer')

@section('script')
    <script type="text/javascript">
        // 固有のScripts
    </script>
@endsection
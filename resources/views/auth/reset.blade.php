@extends('layouts.base')

@section('meta')
    <title>パスワード再設定｜{{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-4 col-md-offset-1">
                    <ul class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">Reset Password</li>
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
                        <div class="panel-heading">パスワード再設定</div>
                        <div class="panel-body">
                            
                            <form class="form-horizontal" role="form" method="POST" action="/auth/password/reset">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="token" value="{{ $token }}" />
                                
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Eメールアドレス</label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" required="required" value="{{ old('email') }}" />
                                    
                                        @if ($errors->has('email'))
                                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">パスワード</label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" name="password" required="required" />
                                    
                                        @if ($errors->has('password'))
                                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">パスワード (確認用)</label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" name="password_confirmation" required="required" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-cog"></span>&nbsp;設定
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div><!-- panel-body -->
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
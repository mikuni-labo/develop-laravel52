@extends('layouts.base')

@section('meta')
    <title>ユーザー登録｜{{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-4 col-md-offset-1">
                    <ul class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li><a href="/user">User</a></li>
                        <li class="active">Add</li>
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
                    
                    <div class="panel panel-info">
                        <div class="panel-heading">ユーザー登録</div>
                        <div class="panel-body">
                        
                            {!! Form::open(['class' => 'form-horizontal']) !!}
                                
                                <div class="form-group{{ $errors->has('name1') || $errors->has('name2') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">お名前<span class="attention">*</span></label>
                                    <div class="col-md-3">
                                        {!! Form::text('name1', null, ['required', 'class' => 'form-control ckeditor', 'placeholder' => '姓']) !!}
                                        
                                        @if ($errors->has('name1'))
                                            <span class="help-block"><strong>{{ $errors->first('name1') }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::text('name2', null, ['required', 'class' => 'form-control ckeditor', 'placeholder' => '名']) !!}
                                    
                                        @if ($errors->has('name2'))
                                            <span class="help-block"><strong>{{ $errors->first('name2') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">会社名&nbsp;&nbsp;</label>
                                    <div class="col-md-6">
                                        {!! Form::text('company', null, ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                                        
                                        @if ($errors->has('company'))
                                            <span class="help-block"><strong>{{ $errors->first('company') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">部署名&nbsp;&nbsp;</label>
                                    <div class="col-md-6">
                                        {!! Form::text('position', null, ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                                        
                                        @if ($errors->has('position'))
                                            <span class="help-block"><strong>{{ $errors->first('position') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}" id="email_response">
                                    <label class="col-md-4 control-label" for="email">E-Mail</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">@</span>
                                            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                                        </div>
                                        <span id="email-inputicon" class="glyphicon glyphicon-ok form-control-feedback"></span>
                                        <span id="email_judge"></span>
                                        
                                        @if ($errors->has('email'))
                                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">パスワード<span class="attention">*</span></label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" name="password" required="required">
                                        
                                        @if ($errors->has('password'))
                                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">パスワード(確認用)<span class="attention">*</span></label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" name="password_confirmation" required="required">
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">権限<span class="attention">*</span></label>
                                    <div class="col-md-6 form-control-static">
                                        @foreach($Fixed['role'] as $key => $val)
                                            <label>{!! Form::radio('role', $key, (old('role') == $key), ['required']) !!}&nbsp;{!! $Fixed['role'][$key] !!}</label><br>
                                        @endforeach
                                        
                                        @if ($errors->has('role'))
                                            <span class="help-block"><strong>{{ $errors->first('role') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">ステータス<span class="attention">*</span></label>
                                    <div class="col-md-6 form-control-static">
                                        <label>{!! Form::radio('status', '1', true         , ['required']) !!}&nbsp;<span class="text-success">有効</span>&nbsp;&nbsp;&nbsp;</label>
                                        <label>{!! Form::radio('status', '0', old('status'), ['required']) !!}&nbsp;<span class="text-danger">無効</span></label>
                                        
                                        @if ($errors->has('status'))
                                            <span class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <a href="javascript:history.back();" class="btn btn-success"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;前の画面に戻る</a>
                                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-import"></span>&nbsp;登録</button>
                                    </div>
                                </div>
                                
                            {!! Form::close() !!}
                        </div><!-- .panel-body -->
                    </div><!-- .panel -->
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
@endsection

@include('layouts.footer')

@section('script')
    <script type="text/javascript" src="/js/user.js"></script>
    <script type="text/javascript" src="/js/change.input.color.js"></script>
    <script type="text/javascript">
        // 固有のScripts
    </script>
@endsection
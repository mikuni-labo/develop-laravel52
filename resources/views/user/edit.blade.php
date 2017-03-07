@extends('layouts.base')

@section('meta')
    <title>ユーザー情報編集｜{{ $Fixed['info']['SiteName'] }}</title>
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
                        <li class="active">Edit</li>
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
                        <div class="panel-heading">ユーザー情報編集</div>
                        <div class="panel-body">
                        
                            {!! Form::open(['class' => 'form-horizontal']) !!}
                                
                                <div class="form-group{{ $errors->has('name1') || $errors->has('name2') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">お名前<span class="attention">*</span></label>
                                    <div class="col-md-3">
                                        {!! Form::text('name1', $row->name1, ['required', 'class' => 'form-control ckeditor', 'placeholder' => '姓']) !!}
                                        
                                        @if ($errors->has('name1'))
                                            <span class="help-block"><strong>{{ $errors->first('name1') }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::text('name2', $row->name2, ['required', 'class' => 'form-control ckeditor', 'placeholder' => '名']) !!}
                                    
                                        @if ($errors->has('name2'))
                                            <span class="help-block"><strong>{{ $errors->first('name2') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">会社名&nbsp;&nbsp;</label>
                                    <div class="col-md-6">
                                        {!! Form::text('company', $row->company, ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                                        
                                        @if ($errors->has('company'))
                                            <span class="help-block"><strong>{{ $errors->first('company') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">部署名&nbsp;&nbsp;</label>
                                    <div class="col-md-6">
                                        {!! Form::text('position', $row->position, ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                                        
                                        @if ($errors->has('position'))
                                            <span class="help-block"><strong>{{ $errors->first('position') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Eメールアドレス<span class="attention">*</span></label>
                                    <div class="col-md-6">
                                        {!! Form::email('email', $row->email, ['required', 'class' => 'form-control ckeditor', 'placeholder' => 'example@example.co.jp']) !!}
                                        
                                        @if ($errors->has('email'))
                                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">パスワード<span class="attention">*</span></label>
                                    <div class="col-md-6">
                                    
                                        {!! Form::text('password', old('password'), ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                                        
                                        @if ($errors->has('password'))
                                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if (Auth::guard('user')->user()->role === 'ADMINISTRATOR')
                                    <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">権限<span class="attention">*</span></label>
                                        <div class="col-md-6 form-control-static">
                                            
                                            @foreach($Fixed['role'] as $key => $val)
                                                <label>{!! Form::radio('role', $key, ($row->role == $key || old('role') == $key), ['required']) !!}&nbsp;{!! $Fixed['role'][$key] !!}</label><br>
                                            @endforeach
                                            
                                            @if ($errors->has('role'))
                                                <span class="help-block"><strong>{{ $errors->first('role') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">ステータス<span class="attention">*</span></label>
                                        <div class="col-md-6 form-control-static">
                                            <label>{!! Form::radio('status', '1', ($row->status == '1' || old('status') == '1'), ['required']) !!}&nbsp;<span class="text-success">有効</span>&nbsp;&nbsp;&nbsp;</label>
                                            <label>{!! Form::radio('status', '0', ($row->status == '0' || old('status') == '0'), ['required']) !!}&nbsp;<span class="text-danger">無効</span></label>
                                            
                                            @if ($errors->has('status'))
                                                <span class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <a href="javascript:history.back();" class="btn btn-success"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;前の画面に戻る</a>
                                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-edit"></span>&nbsp;編集</button>
                                        <a href="/user/delete/{{ $row->id }}" class="btn btn-danger" data-toggle="confirmation" onclick="if(!confirm('本当に削除しますか?')) return false;">
                                            <span class="glyphicon glyphicon-trash"></span>&nbsp;削除
                                        </a>
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
    <script type="text/javascript">
        // 固有のScripts
    </script>
@endsection
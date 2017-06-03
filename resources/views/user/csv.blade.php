@extends('layouts.base')

@section('meta')
    <title>CSV操作｜{{ $Fixed['info']['SiteName'] }}</title>
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
                        <li class="active">CSV</li>
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
                        <div class="panel-heading">CSV操作</div>
                        <div class="panel-body">
                            {!! Form::open(['class' => 'form-horizontal']) !!}

                                <div class="form-group{{ $errors->has('csv_user_import') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">ユーザ登録CSV</label>
                                    <div class="col-md-6 form-control-static">
                                        {!! Form::file('csv_user_import', null) !!}

                                        @if ($errors->has('csv_user_import'))
                                            <span class="help-block"><strong>{{ $errors->first('csv_user_import') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <a href="javascript:history.back();" class="btn btn-success"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;前の画面に戻る</a>
                                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;アップロード</button>
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

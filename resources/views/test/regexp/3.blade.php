@extends('layouts.base')

@section('meta')
    <title>テストページ｜{{ $Fixed['info']['SiteName'] }}</title>
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
@endsection

@include('layouts.gnavi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-4 col-md-offset-1">
                    <ul class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active">Test</li>
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
                    
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->
    </section>
@endsection

@include('layouts.footer')

@section('script')
    <script type="text/javascript">
        var s = '<title>kuniyasuくにやす</title>';
        var res = s.match(/<title>([^<]+)<\/title>/g);
        var reg = RegExp.$1;// 直前にマッチしたものを抽出
        
        if(res)
            console.log('マッチしたぜ〜');
        else
            console.log('非マッチだぜ〜');
        
        console.log(res[0]);
        console.log(reg);
    </script>
@endsection
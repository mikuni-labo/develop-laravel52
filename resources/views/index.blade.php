@extends('layouts.base')

@section('meta')
    <title>{{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
    <section class="section">
        <div class="container">
            <div class="row animated fadeIn">
                <div class="col-md-4 col-md-offset-1">
                    <ul class="breadcrumb">
                        <li class="active">Home</li>
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
                    
                    <div class="panel panel-warning">
                        <div class="panel-heading">トップページ</div>
                        <div class="panel-body">メインコンテンツ部分</div>
                    </div>
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
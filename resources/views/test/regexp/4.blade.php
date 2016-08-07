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
		var s = '2015-09-06 19:42:55';
		var arrRes = s.match(/(\d{4})[-\/](\d{2})[-\/](\d{2})[\s](\d{2})[:](\d{2})[:](\d{2})/);
		
		if(arrRes)
			console.log('マッチしたぜ〜');
		else
			console.log('非マッチだぜ〜');
		
		console.log(RegExp.$1 + '年' + RegExp.$2 + '月' + RegExp.$3 + '日 ' + RegExp.$4 + '時' + RegExp.$5 + '分' + RegExp.$6 + '秒');
	</script>
@endsection
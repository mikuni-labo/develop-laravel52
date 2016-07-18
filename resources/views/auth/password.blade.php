@extends('layouts.base')

@section('meta')
	<title>パスワード再設定メール送信｜{{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
	<section class="section">
		<div class="container">
			<div class="row animated fadeIn">
				<div class="col-md-4 col-md-offset-1">
					<ul class="breadcrumb">
						<li><a href="/">Home</a></li>
						<li class="active">Resend Modify Password Mail</li>
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
					
					@if (session('status'))
						<div class="alert alert-success">{{ session('status') }}</div>
					@endif
					
					<div class="panel panel-default">
						<div class="panel-heading">パスワード再設定メール送信</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="?">
								<input type="hidden" name="_token" value="{{ csrf_token() }}" />
								
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">Eメールアドレス</label>
									<div class="col-md-6">
										<input type="email" class="form-control" name="email" required="required" value="{{ old('email') }}" />
									
										@if ($errors->has('email'))
											<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<a href="javascript:history.back();" class="btn btn-success"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;前の画面に戻る</a>
										<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span>&nbsp;送信</button>
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
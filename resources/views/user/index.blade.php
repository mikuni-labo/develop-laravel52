@extends('layouts.base')

@section('meta')
	<title>ユーザー情報一覧｜{{ $Fixed['info']['SiteName'] }}</title>
@endsection

@include('layouts.gnavi')

@section('content')
	<section class="section">
		<div class="container">
			<div class="row animated fadeIn">
				<div class="col-md-4 col-md-offset-1">
					<ul class="breadcrumb">
						<li><a href="/">Home</a></li>
						<li class="active">User</li>
					</ul>
				</div><!-- .col -->
			</div><!-- .row -->
		</div><!-- .container -->
	</section>
	<section class="section">
		<div class="container">
			<div class="row animated fadeIn">
				<div class="col-md-12 col-md-offset-0">
					
					@include('flash::message')
					
					<h2 class="h2 page-header animated bounce"><span class="glyphicon glyphicon-search"></span>&nbsp;ユーザ検索</h2>
					
					{!! Form::open(['class' => 'form-horizontal']) !!}
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped table-condensed">
								<colgroup>
									<col width="15%">
									<col width="35%">
									<col width="15%">
									<col width="35%">
								</colgroup>
								
								<thead></thead>
								<tbody>
									<tr>
										<th class="text-center" colspan="1">ユーザID</th>
										<td class="text-center" colspan="1">
											<div class="{{ $errors->has('search_user_id') ? 'has-error' : '' }}">
												{!! Form::text('search_user_id', isset($search['search_user_id']) ? $search['search_user_id'] : '', ['class' => 'form-control', 'maxlength' => '255', 'placeholder' => '']) !!}
												
												@if ($errors->has('search_user_id'))
													<span class="help-block"><strong>{{ $errors->first('search_user_id') }}</strong></span>
												@endif
											</div>
										</td>
										<th class="text-center" colspan="1">ステータス</th>
										<td class="text-center" colspan="1">
											<div class="{{ $errors->has('search_status_on') || $errors->has('search_status_off') ? 'has-error' : '' }}">
												<label>{!! Form::checkbox('search_status_on',  '1', (isset($search['search_status_on'])  && $search['search_status_on']  == 1) ? true : false, ['maxlength' => '1']) !!}&nbsp;<span class="text-success">有効</span></label>&nbsp;&nbsp;
												<label>{!! Form::checkbox('search_status_off', '1', (isset($search['search_status_off']) && $search['search_status_off'] == 1) ? true : false, ['maxlength' => '1']) !!}&nbsp;<span class="text-danger">無効</span></label>
												
												@if ($errors->has('search_status_on') || $errors->has('search_status_off'))
													<span class="help-block"><strong>{{ $errors->first('search_status_on') }}</strong></span>
													<span class="help-block"><strong>{{ $errors->first('search_status_off') }}</strong></span>
												@endif
											</div>
										</td>
									</tr>
									<tr>
										<th class="text-center" colspan="1">ユーザ名</th>
										<td class="text-center" colspan="1">
											<div class="{{ $errors->has('search_user_name') ? 'has-error' : '' }}">
												{!! Form::text('search_user_name', isset($search['search_user_name']) ? $search['search_user_name'] : '', ['class' => 'form-control', 'maxlength' => '255', 'placeholder' => '']) !!}
												
												@if ($errors->has('search_user_name'))
													<span class="help-block"><strong>{{ $errors->first('search_user_name') }}</strong></span>
												@endif
											</div>
										</td>
										<th class="text-center" colspan="1">メールアドレス</th>
										<td class="text-center" colspan="1">
											<div class="{{ $errors->has('search_email') ? 'has-error' : '' }}">
												{!! Form::text('search_email', isset($search['search_email']) ? $search['search_email'] : '', ['class' => 'form-control', 'maxlength' => '255', 'placeholder' => '']) !!}
												
												@if ($errors->has('search_email'))
													<span class="help-block"><strong>{{ $errors->first('search_email') }}</strong></span>
												@endif
											</div>
										</td>
									</tr>
									<tr>
										<th class="text-center" colspan="1">削除済みデータ表示</th>
										<td class="text-center" colspan="1">
											<div class="{{ $errors->has('search_delete_flag') ? 'has-error' : '' }}">
												<label>{!! Form::checkbox('search_delete_flag', '1', (isset($search['search_delete_flag']) && $search['search_delete_flag'] == 1) ? true : false, ['maxlength' => '1']) !!}&nbsp;<span class="text-success">ON</span></label>
												
												@if ($errors->has('search_delete_flag'))
													<span class="help-block"><strong>{{ $errors->first('search_delete_flag') }}</strong></span>
												@endif
											</div>
										</td>
										<th class="text-center" colspan="1"></th>
										<td class="text-center" colspan="1">
										
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="text-center">
							<a href="/user/search/reset" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span>&nbsp;検索条件クリア</a>
							<button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp;検索する</button>
						</div>
					{!! Form::close() !!}
					
				</div><!-- .col -->
			</div><!-- .row -->
		</div><!-- .container -->
	</section>
	<section class="section">
		<div class="container">
			<div class="row animated fadeIn">
				<div class="col-md-12 col-md-offset-0">
					
					<h2 class="h2 page-header animated bounce"><span class="glyphicon glyphicon-user"></span>&nbsp;ユーザ一覧</h2>
					
					{!! $results->render() !!}
					
					@if(count($results) > 0)
						<div class="table-responsive">
							<table class="table table-hover table-condensed">
								<colgroup>
									<col width="8%">
									<col width="21%">
									<col width="26%">
									<col width="15%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
								</colgroup>
								
								<tr>
									<th class="text-center">ID</td>
									<th class="text-center">ユーザー名</td>
									<th class="text-center">メールアドレス</td>
									<th class="text-center">権限</td>
									<th class="text-center">ステータス</td>
									<th class="text-center">編集</td>
									<th class="text-center">削除</td>
								</tr>
								
								@foreach($results as $result)
									<tr class="<?php if($result->status == '0' || !empty($result->deleted_at)):?>gray_out<?php endif;?>">
										<td class="text-center">{{ $result->id }}</td>
										<td class="text-center">{{ $result->name1 }} {{ $result->name2 }}</a></td>
										<td class="text-center">{{ $result->email }}</a></td>
										<td class="text-center">{{ $Fixed['role'][$result->role] }}</a></td>
										<td class="text-center">@if( $result->status == '1' ) <span class="text-success">有効</span> @else <span class="text-danger">無効</span> @endif</td>
										
										<td class="text-center"><a href="/user/edit/{{ $result->id }}" class="btn btn-sm btn-success">
											<span class="glyphicon glyphicon-edit"></span>&nbsp;編集</a>
										</td>
										<td class="text-center">
											@if($result->deleted_at)
												<a href="/user/restore/{{ $result->id }}" class="btn btn-sm btn-info" data-toggle="confirmation" onclick="if(!confirm('復旧させますか?')) return false;">
												<span class="glyphicon glyphicon-repeat"></span>&nbsp;復旧</a>
											@else
												<a href="/user/delete/{{ $result->id }}" class="btn btn-sm btn-danger" data-toggle="confirmation" onclick="if(!confirm('本当に削除しますか?')) return false;">
												<span class="glyphicon glyphicon-trash"></span>&nbsp;削除</a>
											@endif
										</td>
									</tr>
								@endforeach
							</table>
						</div>
					@else
						<p>条件に一致するデータがありません...</p>
					@endif
					
					{!! $results->render() !!}
					
					<div class="text-center">
						<a href="/user/add" class="btn btn-primary"><span class="glyphicon glyphicon-import"></span>&nbsp;新規ユーザ登録</a>
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
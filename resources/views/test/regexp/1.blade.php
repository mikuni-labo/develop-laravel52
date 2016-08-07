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
	/*
	メタ文字

	[abc] → かbかcのどれかに当てはまる
	[a-z] → a〜zのどれかに当てはまる
	[^abc] → aかbかc以外のどれでも
	. → 任意の1文字
	^ → 行頭 (^kuniyasu は行頭から始まっているか)
	$ → 行末 (kuniyasu$ は行末で終わっているか)

	{} → 直前の文字が繰り返す回数
	a{2} → aa
	a{2,} → aa, aaa, aaaaaaaaaa, aaaaaaaaaaaaa... (2回以上の繰り返し)
	a{2,4} → aa, aaa, aaaa

	a? → 0 or 1 → , a (空白を含む a)
	a* → 0 or more → , a, aaaa, aaaaaaaaa... (空白含む a の複数回)
	a+ → 1 or more → a, aaa, aaaaaaa, aaaaaaaa...

	() → 文字列をまとめる
	(abc)* → abc, abcabc, abcabcabcabc...
	| → orの意味 論理和
	(abc|xyz) → abc または xyz

	\n → 改行
	\t → タブ
	\d → 数字 [0-9]
	\w → 英数字
	\s → スペース、タブ
	\メタ → メタ文字自身をエスケープする場合 \/, \[など

	? → 最小マッチ

	フラグ
	s.match(/(@[A-Za-z0-9_]{1,15})/i);←ここにオプションとして付ける
	i → 大文字小文字を区別しない → Kuniyasu, kuniYaSu, KUNIYASU などにマッチ
	g → すべてのマッチした要素を配列で返す
	m → 複数行に対応させる → ^や $などは通常は文頭、文末だが、改行コードを含む文章(@kuniyasu\n@tommy\n@ponpoco)などでも各行で対応出来るようになる。

	RegExp → 直前にマッチしたものを抽出

	*/
	
	var s = 'wada@kuniyasu.com';
	var res = s.match(/(.+?)@kuniyasu.com/);
	var reg = RegExp.$1;// 直前にマッチしたものを抽出
	
	console.log(reg);
	console.log(RegExp.$1);
	
	//コンソール確認のためにoption + command + jで開発者ツール呼出しておく事
</script>
@endsection
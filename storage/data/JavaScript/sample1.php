<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScript</title>
</head>

<body>
	<h1>JavaScript</h1>
	<h2>即時関数</h2>
	
<script type="text/javascript">
	/*
		即時関数
		関数定義して、即実行する
		通常通り引数も使える
		メリットとしては、通常の変数をローカル変数として扱えるので、
		他のプログラムからの影響を受けずに安全に変数を扱える。

		構文としては
		(function(name) {
			//処理
		}());
		または
		(function(name) {
			//処理
		})();
		があるが、構文エラーの可能性を下げるために前者が推奨される
		
	*/
//	(functionhello() {
	(function(name) {// 関数名を省略して無名関数としても使える
		var msg = "Hello! " + name;// この msg がローカル変数になる
		//console.log(msg);
		console.log(a(msg));
		
	}("Kuniyasu"));//引数付きで実行

	function a(msg){
		return msg + " Good bye!";
	}
</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

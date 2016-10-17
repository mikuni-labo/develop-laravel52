<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScriptの練習</title>
</head>

<body>
	<h1>JavaScript</h1>
	<h2>自作オブジェクト</h2>

<script type="text/javascript">

	var user = {
		score: 85,               // プロパティ
		greet: function(name) { // メソッド
			var msg = "hello! " + name + " スコア: " + this.score;
			
			document.write(msg);
			console.log(msg);
		}
	};
	
	user.greet("Kuniyasu");

</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>

</body>
</html>

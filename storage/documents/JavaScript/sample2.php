    <!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScriptの練習</title>
</head>

<body>
	<h1>JavaScript</h1>
	<h2>タイマー処理1</h2>

	<input type="button" onclick="show();" value="btn">
	
<script type="text/javascript">
	/*
		タイマー処理
		- setInterval - 前の処理が終わらなくても実行されるので重い処理が蓄積されてブラウザクラッシュの可能性がある
		- setTimeout - 前の処理が終わってから実行される (こちらが推奨される)
	*/
	var i = 0;
	function show() {
		
		if(i > 0)
			console.log(i);
		++i;
		
		var timerID = setTimeout(function(){
			show();
		}, 1000)//1秒毎に表示
		
		//5秒を越えたらタイマー解除
		if(i > 5){
			clearTimeout(timerID);
		}
	}
</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

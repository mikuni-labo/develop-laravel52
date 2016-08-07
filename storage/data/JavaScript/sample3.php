<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScriptの練習</title>
</head>

<body>
	<h1>JavaScript</h1>
	<h2>タイマー処理2</h2>

	開始：<input type="text" name="start_no" id="start_no" size="5" value="">
	秒数：<input type="text" name="cnt_no" id="cnt_no" size="5" value=""><br>
	<button id="submit_btn" onClick="exec();">submit</button>
	
	<div id="target"></div>
<script type="text/javascript">

	/*
		タイマー処理
		- setInterval - 前の処理が終わらなくても実行されるので重い処理が蓄積されてブラウザクラッシュの可能性がある
		- setTimeout - 前の処理が終わってから実行される (こちらが推奨される)
	*/
	function exec(){
		var i   = parseInt(document.getElementById('start_no').value);
		var cnt = parseInt(document.getElementById('cnt_no').value);
		var target = document.getElementById('target');
		var s = i;// スタート値も格納しておく
		
		if(i >= 0 && cnt >= 0)
			show();
		else
			target.innerHTML = "数値を正しく入力してください。";
		
		function show() {
			target.innerHTML = i;
			//console.log(i);
			++i;
			
			var timerID = setTimeout(function(){
				show();
			}, 1000)//1秒毎に表示
			
			//cnt回数を越えたらタイマー解除
			if(i > s + cnt){
				clearTimeout(timerID);
			}
		}
	}
</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>パララックス3</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<style>
		body {
			margin: 0;
			padding: 0;
		}
		#container {
			width: 600px;
			height: 280px;
			background: #eee;
		}
		.box {
			position: fixed;
			opacity: 0.6;
			left: 300px;
		}
		#box1 {
			width: 40px;
			height: 40px;
			top: 140px;
			background: red;
		}
		#box2 {
			width: 50px;
			height: 50px;
			top: 155px;
			background: blue;
		}
		#box3 {
			width: 60px;
			height: 60px;
			top: 165px;
			background: yellow;
		}
		#box4 {
			width: 70px;
			height: 70px;
			top: 175px;
			background: green;
		}
		#box5 {
			width: 80px;
			height: 80px;
			top: 185px;
			background: orange;
		}

	</style>
</head>

<body>
	<div id="container"></div>
	<div id="box1" class="box"></div>
	<div id="box2" class="box"></div>
	<div id="box3" class="box"></div>
	<div id="box4" class="box"></div>
	<div id="box5" class="box"></div>
	
	<script type="text/javascript">
		$(function(){
			$('#container').mousemove(function(e){
				//console.log(e.clientX, e.clientY);
				var cx = $(this).width() / 2;//センターのｘ座標
				var cy = $(this).height() / 2;//センターのｙ座標
				var dx = e.clientX - cx;//センターからのｘ移動量
				var dy = e.clientY - cy;//センターからのｙ移動量
				//ｙ方向
				$('#box1').css('left', cx + dx * 1.1);
				$('#box2').css('left', cx + dx * 1.3);
				$('#box3').css('left', cx + dx * 1.5);
				$('#box4').css('left', cx + dx * 1.7);
				$('#box5').css('left', cx + dx * 1.9);
				//ｘ方向
				$('#box1').css('top', cy + dy * 1.0);
				$('#box2').css('top', cy + dy * 1.1);
				$('#box3').css('top', cy + dy * 1.3);
				$('#box4').css('top', cy + dy * 1.5);
				$('#box5').css('top', cy + dy * 1.7);
			});
		});
	
	</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

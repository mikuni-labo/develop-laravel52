<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>パララックス2</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<style>
		body {
			margin: 0;
			padding: 0;
			height: 3000px;
		}
		.box {
			margin: 0;
			padding: 0;
			height: 640px;
		}
		#bg1 { background: url('p1.jpg') no-repeat; width: 960px;}
		#bg2 { background: url('p2.jpg') no-repeat; width: 960px;}
		#bg3 { background: url('p3.jpg') no-repeat; width: 960px;}
		#msg {
			font-size: 48px;
			font-weight: bold;
			font-family: Verdana, Arial, sans-serif;
			color: orange;
			opacity: 0;
			position: fixed;
		}

	</style>
</head>

<body>
	<div id="bg1" class="box"></div>
	<div id="bg2" class="box"></div>
	<div id="bg3" class="box"></div>
	<div id="msg" class="box">The End.</div>
	
	<script type="text/javascript">
		$(function(){
//		var pos1 = $('#box1').offset();
//		var pos2 = $('#box2').offset();
//		var pos3 = $('#box3').offset();
			
			$(window).scroll(function(){
				var dy = $(this).scrollTop();
				
				// 1枚目の画像
				$('#bg1').css('background-position', '0 '+ dy + 'px');
				
				// 2枚目の画像
				if(dy > 640){
					//$('#bg2').css('background-position', '0 ' + (dy-640) + 'px');
					$('#bg2').css('background-position', (dy-640) + 'px ' + (dy-640) + 'px');
				}
				else {
					$('#bg2').css('background-position', '0 0');
				}
				
				// 3枚目の画像
				if(dy > 1280){
					$('#bg3').css('background-position', '0 '+ (dy-1280)*2 + 'px');
					$('#msg').css('opacity', (dy-1280)/640);
					$('#msg').css('top', 200);
					var dx = (dy-1280) > 400 ? 400: (dy-1280);
					$('#msg').css('left', dx);
				}
				else {
					$('#bg3').css('background-position', '0 0');
					$('#msg').css('opacity', 0);
				}
			});
		});
	
	
	</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

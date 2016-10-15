<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>parallax</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<style>
		body {
			margin: 0;
			padding: 0;
			height: 3000px;
		}
		.box {
			height: 40px;
			width: 40px;
			position: fixed;
			top: 10px;
		}
		#box1 { left: 10px; background: red;}
		#box2 { left: 60px; background: blue;}
		#box3 { left: 110px; background: green;}

	</style>
</head>

<body>
	<div id="box1" class="box"></div>
	<div id="box2" class="box"></div>
	<div id="box3" class="box"></div>
	
	<script type="text/javascript">
		$(function(){
			var pos1 = $('#box1').offset();
			var pos2 = $('#box2').offset();
			var pos3 = $('#box3').offset();
			
			$(window).scroll(function(){
				var dy = $(this).scrollTop();
				console.log(dy);
				
				$('#box1').css('top', pos1.top + dy / 2);
				$('#box1').css('left', pos1.left + dy / 8);
//				$('#box1').css('width', 40 + dy * 0.2);
				
				$('#box2').css('top', pos2.top + dy / 5);
				$('#box2').css('left', pos2.left + dy / 2);
				
				$('#box3').css('top', pos3.top + dy / 7);
				$('#box3').css('left', pos3.left + dy / 4);
			});
		});
	
	
	</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

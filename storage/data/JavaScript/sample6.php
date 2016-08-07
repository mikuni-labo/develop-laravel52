<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScriptの練習</title>
</head>

<body onload="nowTime()">
	<h1>JavaScript</h1>
	<h2>時計</h2>

	<form name="F1" action="#">
		<input type="text" name="T1" value="" size="15" />
	</form>
	
	<script type="text/javascript">
	
		function nowTime() {
		    dd = new Date();
		    document.F1.T1.value = dd.toLocaleString();
		    window.setTimeout("nowTime()", 1000);
		}
		
	</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScriptの練習</title>
</head>

<body>
	<h1>JavaScript</h1>
	<h2>組み込みオブジェクト</h2>

	<script type="text/javascript">
	/*
		組み込みオブジェクト
		- String
		- Array
		- Math
		- Date
	*/

// Stringオブジェクト
//	var s = new String("Kuniyasu");// Stringオブジェクト
	var str = "Kuniyasu";// 文字列リテラル
											// (JavaScriptがStringオブジェクトと同じメソッドやプロパティを使えるように一時的に変換してくれる)
//	console.log("文字数" + str.length);

// Arrayオブジェクト
	var arr = new Array(100, 200, 300);
//	console.log(arr.length);// 要素数
	// (先頭に挿入) unshift -> array <- push (末尾に挿入)
	// (先頭から取得) shift	 <- array -> pop (末尾から取得)
	
	arr.push(500);// 末尾に500を挿入
	var a = arr.pop();// 末尾から取得
	console.log(a);// 取得したもの
	console.log(arr);// 現在の配列
	
//	arr.splice(1, 2);// 1番目から２つ削除
	arr.splice(1, 2, 500, 800);// 1番目から２つ削除して、その場所に500と800を挿入
	console.log(arr);// 現在の配列

	</script>

<div>
	<a href="" onKeyPress="history.back();return false" onClick="history.back();return false">Back</a>
	<a href="/">Top</a>
</div>
</body>
</html>

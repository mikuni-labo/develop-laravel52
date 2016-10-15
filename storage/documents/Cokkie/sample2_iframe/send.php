<?php
/**
 * このソースを別ドメインへ置いて検証する
 */
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>動作確認</title>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script type="text/javascript">
$(window).load(function() {
	  var url = 'http://eccube.local/test/cokkie_test/';

  $('#CookieWrite').click(function(){
    setElement(url + 'cookie_write.php');
    $('#Message').html('クッキーを書き込みました');
  });
  $('#CookieRead').click(function(){
    setElement(url + 'cookie_read.php');
  });
  $('#CookieDelete').click(function(){
    setElement(url + 'cookie_delete.php');
    $('#Message').html('クッキーを削除しました');
  });
});

function setElement(url) {
  url = url + '?' + String((new Date()).getTime());
  scriptElement = document.createElement('script');
  scriptElement.type = 'text/javascript';
  scriptElement.src = url;
  document.body.appendChild(scriptElement);
}

function cookie_read(data) {
  $('#Message').html(data);
}
</script>
</head>
<body>
<button id="CookieWrite">クッキー書き込み</button>
<button id="CookieRead">クッキー読み込み</button>
<button id="CookieDelete">クッキー削除</button>
<div id="Message"></div>

<iframe src="http://eccube.local/" name="in" width="1200" height="500"></iframe>
</body>
</html>
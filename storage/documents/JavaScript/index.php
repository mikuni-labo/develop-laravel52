<?php
/*
 * indexページ
 * 
 * @author	Kuniyasu Wada
 * @package	index.php
 */
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>JavaScript</title>
</head>

<body>
	
	<h1>JavaScript</h1>
	<ul>
<?php 
// ディレクトリ内のファイルをスキャンして、sampleファイル数をカウント
$cnt = count(scandir("./")) - 3;// 'index.php', '.', '..' の3つを省く

?>
		<?php for($i=1; $i<=$cnt; $i++):?>
			<li><a href="./sample<?= $i ?>.php">sample<?= $i ?>.php</a></li>
		<?php endfor;?>
	</ul>

<a href="/">Top</a>
</body>
</html>
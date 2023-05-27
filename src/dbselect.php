<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<title>dbselect.php</title>
</head>

<body>
	<?php
	$dsn = 'mysql:host=localhost;dbname=php;charset=utf8'; // データソース名・・データベースは「php」	・・・①
	$user = 'kobe';											// ユーザー名	・・・①
	$password = 'denshi';								// パスワード	・・・①

	try {
		$pdo = new PDO($dsn, $user, $password);		// データベースへ接続するオブジェクト作成	
		$sql = 'select  *  from  person';					// SQL文の定義	
		$stmt = $pdo->query($sql);							  // SELECT文の実行 ・・・②
		$results = $stmt->fetchAll();						  // 実行結果を連想配列の形で取り出す ・・・③	
		foreach ($results  as  $result) {					// 配列のデータを1件ずつ処理する	
			echo 'uid=' . $result['uid'] . ', name=' . $result['name'] . '<br>';
		}
	} catch (Exception $e) {
		echo 'Error:' . $e->getMessage();
		die();
	}
	$pdo = null;		// データベースへの接続を閉じる ・・・④	
	?>
	<hr>
	<h4>0J0X0XX 神戸電子</h4>
</body>

</html>
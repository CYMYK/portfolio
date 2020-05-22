<?php
	//ホスト、データベース名
$dsn = 'mysql:host=*********;dbname=reservation';
 
 	//データベースのユーザー名
$user = '**********';
 
	//データベースのパスワード
$password = '************';
 
try {
	//インスタンス生成
	$db = new PDO($dsn, $user, $password);
 
} catch (PDOException $e) {
	//エラーメッセージ
	echo 'データベースにアクセスできません' . $e->getMessage();
 
	exit;
 
}
?>
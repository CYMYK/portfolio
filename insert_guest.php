<?php

//DB接続情報読み込み
require_once 'DbManager.php';
//関数定義読み込み
require_once 'function.php';


//入力エラーメッセージが格納される。
$err_msg = '';
//エラーフラグ、入力エラーが発生するとtrue
$err_flg = false;

//入力チェック
if (!not_entered_check($_POST['name'])) {
	$err_msg = '名前が入力されていません<br>';
	$err_flg = true;
}

if (!not_entered_check($_POST['address'])) {
	$err_msg .= '住所が入力されていません<br>';
	$err_flg = true;
}

if (!not_entered_check($_POST['tel'])) {
	$err_msg .= '電話番号が入力されていません<br>';
	$err_flg = true;
} else if (!number_check($_POST['tel'])) {
	$err_msg .= '電話番号は数字だけを入力してください<br>';
	$err_flg = true;
}

try{

switch($_POST['button']) {

	case $_POST['button'] == '登録' and $err_flg == false;
	//新規登録
	
	$sql = 'INSERT INTO guest (name,address,tel,memo) VALUES (:name,:address,:tel,:memo)';
	$stmt = $db->prepare($sql);
	//値を挿入
	$stmt -> bindValue(':name',$_POST['name']);
	$stmt -> bindValue(':address',$_POST['address']);
	$stmt -> bindValue(':tel',$_POST['tel']);
	$stmt -> bindValue(':memo',$_POST['memo']);
	
	//SQL実行
	$stmt -> execute();
	
	//登録したゲストのIDを取得
	$guest_id = $db->lastInsertId('guest_id');
	$err_msg = '完了しました<br>';
	break;
	
	case $_POST['button'] == '更新' and $err_flg == false;
	//更新
	$sql = 'UPDATE guest SET name = :name,address = :address,tel = :tel,memo = :memo WHERE guest_id = :guest_id';
	$stmt = $db->prepare($sql);

	$stmt -> bindValue(':name',$_POST['name']);
	$stmt -> bindValue(':address',$_POST['address']);
	$stmt -> bindValue(':tel',$_POST['tel']);
	$stmt -> bindValue(':memo',$_POST['memo']);
	$stmt -> bindValue(':guest_id',$_POST['guest_id']);
	
	$stmt -> execute();
	$err_msg = '完了しました<br>';
	break;
	
	case '削除':
	//削除
	$sql = 'DELETE FROM guest WHERE guest_id = :guest_id';
	$stmt = $db->prepare($sql);
	
	$stmt -> bindValue(':guest_id',$_POST['guest_id']);
	
	$stmt -> execute();
	
	//削除したゲストの予約データも同時に削除する
	$sql = 'DELETE FROM reservation WHERE guest_id = :guest_id';
	$stmt = $db->prepare($sql);
	
	$stmt -> bindValue(':guest_id',$_POST['guest_id']);
	
	$stmt -> execute();
	
	$err_msg = '完了しました<br>';
	break;
}



}catch(PDOException $e){

//エラーメッセージ
	echo '失敗しました' . $e->getMessage();
 
	exit;
}
?>


<html>
<body>
<?php
	//遷移元により戻り先を変える
	if (isset($_POST['room'])) {
		echo $err_msg;
		if($err_flg) {
			//失敗　入力エラー　新規ゲスト登録へ戻る
			echo '<a href = "guest_data.php?room='.$_POST['room'].'&'.'r_date='.$_POST['r_date'];
			echo '&name='.$_POST['name'].'&address='.$_POST['address'].'&tel='.$_POST['tel'].'&memo='.$_POST['memo'].'">';
		} else {
			//成功　予約登録へ戻る　
			echo '<a href = "reservation_new.php?room='.$_POST['room'].'&'.'r_date='.$_POST['r_date'].'&'.'edit='.$guest_id.'">';
		}
		echo '戻る</a>';
	} else {
		echo $err_msg;
		if($err_flg) {
			//失敗　入力エラー　新規ゲスト登録へ戻る
			echo '<a href = "guest_data.php?edit='.$_POST['guest_id'];
			echo '&name='.$_POST['name'].'&address='.$_POST['address'].'&tel='.$_POST['tel'].'&memo='.$_POST['memo'].'">';
		} else {
			//成功　ゲスト検索画面へ戻る　
			echo '<a href = "guest_search.php">';
		}
		echo '戻る</a>';
	}
?>
</body>
</html>

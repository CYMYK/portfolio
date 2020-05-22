<?php

require_once 'DbManager.php';
require_once 'function.php';

//入力エラーメッセージが格納される。
$err_msg = '';
//日付チェックに使用
$err_check_in = false;
$err_check_out = false;
//エラーフラグ、入力エラーが発生するとtrue
$err_flg = false;
//エラーチェック
if ($_POST['guest_id'] == "") {
	$err_msg .= 'ゲストが選択されていません<br>';
	$err_flg = true;
}

if (!not_entered_check($_POST['check_in'])) {
	$err_msg .= 'チェックイン日が入力されていません<br>';
	$err_flg = true;
} else if (!date_check($_POST['check_in'])) {
	$err_msg .= 'チェックイン日は 2018-12-10 形式で入力してください<br>';
	$err_flg = true;
} else {
	$err_check_in = true;
}

if (!not_entered_check($_POST['check_out'])) {
	$err_msg .= 'チェックアウト日が入力されていません<br>';
	$err_flg = true;
} else if (!date_check($_POST['check_out'])) {
	$err_msg .= 'チェックアウト日は 2018-12-10 形式で入力してください<br>';
	$err_flg = true;
} else {
	$err_check_out = true;
}

//日付が入力されており、形式が正しい場合チェックを行う
if($err_check_in and $err_check_out){
	if (strtotime(date('Y-m-d')) > strtotime($_POST['check_in'])) {
		$err_msg .= 'チェックイン日は今日以降の日付を入力してください<br>';
		$err_flg = true;
	}
	if (strtotime($_POST['check_in']) >= strtotime($_POST['check_out'])) {
		$err_msg .= 'チェックアウト日はチェックイン日より後の日付を入力してください<br>';
		$err_flg = true;
	}
	
	//予約日付重複チェック
	if (!$err_flg) {
		$sql = 'SELECT COUNT(*) AS check_count FROM reservation';
		$sql .= ' WHERE room ='.$_POST['room'].' AND check_in < "'.$_POST['check_out'].'" AND check_out > "'.$_POST['check_in'].'"';
		//更新の場合、自身の予約データは除外して調べる
		if ($_POST['button'] == '更新') {
			$sql .= ' AND reservation_id NOT IN('.$_POST['reservation_id'].')';
		}
		$stmt = $db->query($sql);
		$check_count = $stmt->fetch();
		if (!$check_count['check_count'] == '0') {
			$err_msg .= '予約が重複しています<br>';
			$err_flg = true;
		}
	} 
	
}

if (!not_entered_check($_POST['adult'])) {
	$err_msg .= '大人が入力されていません<br>';
	$err_flg = true;
} else if (!number_check($_POST['adult'])) {
	$err_msg .= '大人は数字だけを入力してください<br>';
	$err_flg = true;
}

if (!not_entered_check($_POST['children'])) {
	$err_msg .= '小人が入力されていません<br>';
	$err_flg = true;
} else if (!number_check($_POST['children'])) {
	$err_msg .= '小人は数字だけを入力してください<br>';
	$err_flg = true;
}

try{

switch($_POST['button']) {

	case $_POST['button'] == '予約登録' and $err_flg == false;
	//新規登録
	
	$sql = 'INSERT INTO reservation (guest_id,room,check_in,check_out,adult,children,memo) VALUES (:guest_id,:room,:check_in,:check_out,:adult,:children,:memo)';
	$stmt = $db->prepare($sql);
	//値を挿入
	$stmt -> bindValue(':guest_id',$_POST['guest_id']);
	$stmt -> bindValue(':room',$_POST['room']);
	$stmt -> bindValue(':check_in',$_POST['check_in']);
	$stmt -> bindValue(':check_out',$_POST['check_out']);
	$stmt -> bindValue(':adult',$_POST['adult']);
	$stmt -> bindValue(':children',$_POST['children']);
	$stmt -> bindValue(':memo',$_POST['memo']);
	
	//SQL実行
	$stmt -> execute();
	$err_msg = '完了しました<br>';
	break;
	
	case $_POST['button'] == '更新' and $err_flg == false;
	//削除
	$sql = 'UPDATE reservation SET check_in = :check_in,check_out = :check_out,adult = :adult,children = :children,memo = :memo WHERE reservation_id = :reservation_id';
	$stmt = $db->prepare($sql);

	$stmt -> bindValue(':check_in',$_POST['check_in']);
	$stmt -> bindValue(':check_out',$_POST['check_out']);
	$stmt -> bindValue(':adult',$_POST['adult']);
	$stmt -> bindValue(':children',$_POST['children']);
	$stmt -> bindValue(':memo',$_POST['memo']);
	$stmt -> bindValue(':reservation_id',$_POST['reservation_id']);
	
	$stmt -> execute();
	$err_msg = '完了しました<br>';
	break;
	
	case '削除':
	//削除
	$sql = 'DELETE FROM reservation WHERE reservation_id = :reservation_id';
	$stmt = $db->prepare($sql);
	
	$stmt -> bindValue(':reservation_id',$_POST['reservation_id']);
	
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
	echo $err_msg.'<br>';
	if ($err_flg and $_POST['button'] == '予約登録') {
		//失敗、新規予約へ戻る
		echo '<a href = "reservation_new.php?room='.$_POST['room'].'&edit='.$_POST['guest_id'];
		echo '&r_date='.$_POST['r_date'].'&check_in='.$_POST['check_in'].'&check_out='.$_POST['check_out'];
		echo '&adult='.$_POST['adult'].'&children='.$_POST['children'].'&memo='.$_POST['memo'].'">';
	} else if ($err_flg and $_POST['button'] == '更新') {
		//失敗、予約更新へ戻る
		echo '<a href = "reservation_data.php?room='.$_POST['room'].'&r_date='.$_POST['r_date'].'">';
	} else {
		//成功、メインメニューへ戻る
		echo '<a href = "index.php">';
	}
	echo '戻る</a>';
?>
</body>
</html>

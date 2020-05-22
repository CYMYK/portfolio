<?php
//DB接続情報読み込み
require_once 'DbManager.php';

//更新の場合はゲストIDで抽出する

if (isset($_GET['edit']) and !$_GET['edit'] == '') {
	$guest_id = $_GET['edit'];
} else {
	//新規登録はIDを0にして抽出を0件にする
	$guest_id = 0;
}


//入力エラーで戻ってきた時、入力していた内容を保持する
if (isset($_GET['name'])) {
	$name = ($_GET['name']);
	$address = ($_GET['address']);
	$tel = ($_GET['tel']);
	$memo = ($_GET['memo']);
} else {
	//初回
	$name = '';
	$address = '';
	$tel = '';
	$memo = '';
}

//guest_searchで選択されたゲストを抽出
$sql = 'SELECT guest_id,name,address,tel,memo FROM guest WHERE guest_id ='.$guest_id;
$stmt = $db->query($sql);

foreach ($stmt as $row) {
    $name = $row['name'];
	$address = $row['address'];
	$tel = $row['tel'];
	$memo = $row['memo'];
}

?>

<html>
<title>
ゲスト詳細
</title>
<body>
<table>
<form method="POST" action="insert_guest.php">
	<tr>
		<td>ゲストID</td><td><?php if($guest_id==0){echo "";}else{echo $guest_id;} ?><input type="hidden" name="guest_id" value="<?php echo $guest_id; ?>"></td>
	</tr>
	<tr>
		<td>氏名</td><td><input style="width:250px;" type="text" name="name" value="<?php echo $name; ?>"></td>
	</tr>
	<tr>
		<td>住所</td><td><input style="width:400px;" type="text" name="address" value="<?php echo $address; ?>"></td>
	</tr>
	<tr>
		<td>電話番号</td><td><input style="width:250px;" type="text" name="tel" value="<?php echo $tel; ?>"></td>
	</tr>
	<tr>
		<td>備考欄</td><td><textarea name="memo" style = "width:250px;height:80px;resize:none;"><?php echo $memo; ?></textarea></td>
	</tr>
	<tr>
		<td>
		<?php 
			//新規登録
			if($guest_id == 0) {
				//予約登録から遷移してきた時は部屋番号と日付を渡す
				if (isset($_GET['room'])) {
				echo '<input type="hidden" name="room" value="'.$_GET['room'].'">';
				echo '<input type="hidden" name="r_date" value="'.$_GET['r_date'].'">';
				}
				
				echo '<input type="submit" name="button" value="登録">';
			} else {
				//更新
				echo '<input type="submit" name="button" value="更新">';
				echo '<input type="submit" name="button" value="削除">';
			}
		?>
</form>
		</td>
		<td>
		<?php 
			//入力をやめて遷移元に戻る
			if (isset($_GET['room'])) {
			//予約登録へ戻る
				echo '<form action="reservation_new.php" method="get">';
				echo '<input type="submit" name="button" value="戻る">';
				echo '<input type="hidden" name="room" value="'.$_GET['room'].'">';
				echo '<input type="hidden" name="r_date" value="'.$_GET['r_date'].'">';
			} else {
			//ゲスト検索へ戻る
				echo '<form action="guest_search.php" method="post">';
				echo '<input type="submit" name="button" value="戻る">';
			}
		?>
		</form>
		</td>
	</tr>
</table>
</body>
</html>

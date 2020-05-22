<?php

require_once 'DbManager.php';

//検索ワード代入
if (isset($_GET['name'])) {

	$name = ($_GET['name']);
	$address = ($_GET['address']);
	$tel = ($_GET['tel']);
	
} else {
    //初回
	$name = '';
	$address = '';
	$tel = '';
}

//入力エラーで戻ってきた時、入力していた内容を保持する
if (isset($_GET['check_in'])) {
	$check_in = ($_GET['check_in']);
	$check_out = ($_GET['check_out']);
	$adult = ($_GET['adult']);
	$children = ($_GET['children']);
	$memo = ($_GET['memo']);
} else {
	//初回
	$check_in = $_GET['r_date'];
	//チェックアウト日はチェックイン日の翌日を初期値にする
	$a = $_GET['r_date'];
	$check_out = date('Y-m-d',strtotime("+1day $a"));
	
	$adult = '';
	$children = '';
	$memo = '';
}

//検索結果の件数を調べる
$sql = "SELECT COUNT(*) AS max_count FROM guest WHERE name LIKE '%$name%' AND address LIKE '%$address%' AND tel LIKE '%$tel%' LIMIT 10";
$stmt = $db->query($sql);
$max_count = $stmt->fetch();

//表示するページをセット　1ページ=0 2ページ=10 3ページ=20   表示したい件数分増える
if (isset($_GET["page"]) and $_GET["page"] > 0 and $_GET["page"] <= $max_count['max_count']) {
	$page = (int)$_GET["page"];
} else {
	$page = 0;
}

//入力した検索ワードでゲストのデータを抽出
$sql = "SELECT * FROM guest WHERE name LIKE '%$name%' AND address LIKE '%$address%' AND tel LIKE '%$tel%' LIMIT 10";
//OFFSET句で表示するページを指定
$sql .= " OFFSET $page"; 
$stmt = $db->query($sql);


//ゲストが挿入されているかチェック
if (isset($_GET['edit']) and !$_GET['edit'] == '') {
	$guest_id = $_GET['edit'];
} else {
	//初回
	$guest_id = 0;
}

//予約データへ挿入するためにラジオボタンで選択したゲストのデータを抽出
$sql2 = "SELECT guest_id,name,tel FROM guest WHERE guest_id = $guest_id";
$stmt2 = $db->query($sql2);
//結果を取り出し
$guest = $stmt2->fetch();

?>

<html>
<title>
新規予約
</title>
<body>
<table>
<tr>
<td valign="top">
	部屋番号  <?php echo $_GET['room']; ?>
	<table>
	<form method="POST" action="insert_reservation.php">
	<input type="hidden" name="guest_id" value="<?php echo $guest['guest_id'] ?>">
	<input type="hidden" name="room" value="<?php echo $_GET['room'] ?>">
	<input type="hidden" name="r_date" value="<?php echo $_GET['r_date'] ?>">
		<tr>
			<td>ゲストID</td><td><?php echo $guest['guest_id']; ?></td>
		</tr>
		<tr>
			<td>氏名</td><td><?php echo $guest['name']; ?></td>
		</tr>
		<tr>
			<td>連絡先</td><td><?php echo $guest['tel']; ?></td>
		</tr>
		<tr>
			<td>チェックイン予定日</td><td><input type="text" name="check_in" value="<?php echo $check_in; ?>"></td>
		</tr>
		<tr>
			<td>チェックアウト予定日</td><td><input type="text" name="check_out" value="<?php echo $check_out; ?>"></td>
		</tr>
		<tr>
			<td>大人</td><td><input type="text" name="adult" value="<?php echo $adult; ?>"></td>
		</tr>
		<tr>
			<td>子供</td><td><input type="text" name="children" value="<?php echo $children; ?>"></td>
		</tr>
		<tr>
			<td>備考欄</td><td><textarea name="memo" style = "width:173px;height:80px;resize:none;"><?php echo $memo; ?></textarea></td>
		</tr>
		<tr>
			<td><input type="submit" name="button" value="予約登録"></td>
	</form>
			<form  method="get" action="index.php">
			<td align="right"><input type="submit" name="button" value="戻る"></td>
			<input type="hidden" name="r_date" value="<?php echo $_GET['r_date'] ?>">
			</form>
		</tr>

	</table>
</td>
<td valign="top">
	<form action="guest_data.php" method="get">
	<input type="submit" name="button" value="新規ゲスト登録">
	<input type="hidden" name="room" value="<?php echo $_GET['room'] ?>">
	<input type="hidden" name="r_date" value="<?php echo $_GET['r_date'] ?>">
	</form>
	<table>
	<form action="" method="get">
	<input type="hidden" name="room" value="<?php echo $_GET['room'] ?>">
	<input type="hidden" name="r_date" value="<?php echo $_GET['r_date'] ?>">
		<tr>
			<td><input type="submit" value="検索"></td>
		</tr>
		<tr>
			<td>名前</td>
			<td><input style="width:250px;" type="text" name="name" value="<?php echo $name ?>"></td>
		</tr>
		<tr>
			<td>住所</td>
			<td><input style="width:250px;" type="text" name="address" value="<?php echo $address ?>"></td>
		</tr>
		<tr>
			<td>電話</td>
			<td><input style="width:250px;" type="text" name="tel" value="<?php echo $tel ?>"></td>
		</tr>
	
	</form>
	</table>
	<form action="" method="get">
	<input type="hidden" name="room" value="<?php echo $_GET['room'] ?>">
	<input type="hidden" name="r_date" value="<?php echo $_GET['r_date'] ?>">
	<input type="submit" value="クリア">
	</form>
	<br>
	<table>
	<tr>
		<td>
		<form action="" method="get">
		<input type="submit" name="button" value="挿入">
		<input type="hidden" name="room" value="<?php echo $_GET['room'] ?>">
		<input type="hidden" name="r_date" value="<?php echo $_GET['r_date'] ?>">
		</td>
			<td align="left" style="width:140px;">
		<?php if ($page >= 10) : ?>
      		<a href="?page=<?php echo ($page - 10).'&edit='.$guest_id.'&room='.$_GET['room'].'&r_date='.$_GET['r_date'].'&name='.$name.'&address='.$address.'&tel='.$tel; ?>">前のページへ</a>
    	<?php endif; ?>
		</td>
		<td align="right" style="width:140px;">
			<?php if (($page + 10) < $max_count['max_count']) : ?>
	    　　	<a href="?page=<?php echo ($page + 10).'&edit='.$guest_id.'&room='.$_GET['room'].'&r_date='.$_GET['r_date'].'&name='.$name.'&address='.$address.'&tel='.$tel; ?>">次のページへ</a>
	    	<?php endif; ?>
		</td>
	</tr>
	</table>
	<table border="1">
	<tr>
		<td style="width:50px;">ID</td><td style="width:150px;">氏名</td><td style="width:350px">住所</td><td style="width:120px;">電話番号</td>
	</tr>
		<?php
			//最初のデータを識別するためのカウンター
			$no = 0;
			//一覧表示
			foreach ($stmt as $row) {
				echo '<tr>';
				echo '<td>'.$row['guest_id'].'</td><td>'.$row['name'].'</td><td>'.$row['address'].'</td><td>'.$row['tel'].'</td>';

				switch ($no) {
					case 0:
					//最初のデータのラジオボタンをチェックされた状態にする
					echo '<td><input type="radio" name="edit" value="'.$row['guest_id'].'" checked="checked"></td>';
					break;
		    	default:
					echo '<td><input type="radio" name="edit" value="'.$row['guest_id'].'"></td>';
					break;
				}
				echo '</tr>';
				
				$no++;
			}
		?>
	</form>
	</table>
</td>
</tr>
</table>
</body>
</html>
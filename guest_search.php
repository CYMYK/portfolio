<?php
//DB接続情報読み込み
require_once 'DbManager.php';

//検索ワードが入っているかチェック
if (isset($_GET['name']) or isset($_GET['address']) or isset($_GET['tel'])) {

	$name = ($_GET['name']);
	$address = ($_GET['address']);
	$tel = ($_GET['tel']);
	
} else {
	
	$name = '';
	$address = '';
	$tel = '';
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

//検索ワードで抽出
$sql = "SELECT * FROM guest WHERE name LIKE '%$name%' AND address LIKE '%$address%' AND tel LIKE '%$tel%' LIMIT 10";
//OFFSET句で表示するページを指定
$sql .= " OFFSET $page"; 
$stmt = $db->query($sql);

?>

<html>
<title>
ゲスト検索
</title>
<body>
<table>
<form action="" method="get">
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
<input type="submit" value="クリア">
</form>
<table>
<tr>
	<form action="guest_data.php" method="get">
	<td><input type="submit" name="button" value="新規ゲスト登録"></td>
	</form>
	<form action="guest_data.php" method="get">
	<td><input type="submit" name="button" value="編集"></td>
	<td align="left"  style="width:140px;">
		<?php if ($page >= 10) : ?>
      		<a href="?page=<?php echo ($page - 10).'&name='.$name.'&address='.$address.'&tel='.$tel; ?>">前のページへ</a>
    	<?php endif; ?>
	</td>
	<td align="right" style="width:140px;">
		<?php if (($page + 10) < $max_count['max_count']) : ?>
    　　	<a href="?page=<?php echo ($page + 10).'&name='.$name.'&address='.$address.'&tel='.$tel; ?>">次のページへ</a>
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
<form action="index.php" method="post">
<input type="submit" name="button" value="戻る">
</form>
</body>
</html>



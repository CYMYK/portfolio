<?php
//DB接続情報読み込み
require_once 'DbManager.php';

//index.phpで選択された部屋の予約データを抽出
$sql = 'SELECT reservation.reservation_id,guest.guest_id,';
$sql.= 'guest.name,guest.tel,reservation.check_in,reservation.check_out,';
$sql.= 'reservation.adult,reservation.children,reservation.memo';
$sql.= ' FROM reservation INNER JOIN guest';
$sql.= ' ON reservation.guest_id = guest.guest_id';
$sql.= ' WHERE reservation.room = '.$_GET['room'];
$sql.= ' AND check_in <= "'.$_GET['r_date'].'" AND "'.$_GET['r_date'].'" < check_out';
$stmt = $db->query($sql);
$data = $stmt->fetch();
?>

<html>
<title>
予約詳細
</title>
<body>
部屋番号  <?php echo $_GET['room']; ?>
<table>
	<form method="POST" action="insert_reservation.php">
	<input type="hidden" name="reservation_id" value="<?php echo $data['reservation_id']; ?>">
	<input type="hidden" name="guest_id" value="<?php echo $data['guest_id']; ?>">
	<input type="hidden" name="room" value="<?php echo $_GET['room']; ?>">
	<input type="hidden" name="r_date" value="<?php echo $_GET['r_date']; ?>">
	<tr>
		<td>ゲストID</td><td><?php echo $data['guest_id']; ?></td>
	</tr>
	<tr>
		<td>氏名</td><td><?php echo $data['name']; ?></td>
	</tr>
	<tr>
		<td>連絡先</td><td><?php echo $data['tel']; ?></td>
	</tr>
	<tr>
		<td>チェックイン予定日</td><td><input type="text" name="check_in" value="<?php echo $_GET['r_date']; ?>"></td>
	</tr>
	<tr>
		<td>チェックアウト予定日</td><td><input type="text" name="check_out" value="<?php echo $data['check_out']; ?>"></td>
	</tr>
	<tr>
		<td>大人</td><td><input type="text" name="adult" value="<?php echo $data['adult']; ?>"></td>
	</tr>
	<tr>
		<td>子供</td><td><input type="text" name="children" value="<?php echo $data['children']; ?>"></td>
	</tr>
	<tr>
		<td>備考欄</td><td><textarea name="memo" style = "width:173px;height:80px;resize:none;"><?php echo $data['memo']; ?></textarea></td>
	</tr>
	<tr>
		<td>
		<input type="submit" name="button" value="更新">
		<input type="submit" name="button" value="削除">
		</td>
	</form>
		<td align="right">
		<form  method="get" action="index.php">
		<input type="submit" name="button" value="戻る">
		<input type="hidden" name="r_date" value="<?php echo $_GET['r_date']; ?>">
		</form>
		</td>
	</tr>	
</table>


</body>
</html>
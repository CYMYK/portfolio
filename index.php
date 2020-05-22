<?php
//DB接続情報読み込み
require_once 'DbManager.php';

//部屋数
$MAX_ROOM = 12;
//階数
$MAX_FLOOR = 3;


//次の日、前の日、次の月、前の月を押した時、日付を進める
if (isset($_GET['yesterday'])) {
	$a = $_GET['yesterday'];
	$r_date = date('Y-m-d',strtotime("-1day $a"));
} else if (isset($_GET['tomorrow'])) {
	$a = $_GET['tomorrow'];
	$r_date = date('Y-m-d',strtotime("+1day $a"));
} else if (isset($_GET['previous_month'])) {
	$a = $_GET['previous_month'];
	$r_date = date('Y-m-d',strtotime("-1month $a"));
} else if (isset($_GET['next_month'])) {
	$a = $_GET['next_month'];
	$r_date = date('Y-m-d',strtotime("+1month $a"));
} else if (isset($_GET['r_date'])) {
	//予約新規登録、予約詳細画面から戻ってきた時、見ていた日付へ戻る
	$r_date = $_GET['r_date'];
} else {
	//初回　今日の日付を取得
	$r_date = date('Y-m-d');
}

//指定された日付の予約データを抽出
$sql = 'SELECT room FROM reservation WHERE check_in <= "'.$r_date.'" AND "'.$r_date.'" < check_out';
$stmt = $db->query($sql);

//抽出したデータを配列に格納
$arr = array();
foreach($stmt as $row) {
	$arr[] = $row['room'];
}

?>

<html>
<title>
メインメニュー
</title>
<body>

<table>
<tr>
<td valign="top">

<a href="index.php?<?php echo "previous_month=$r_date"; ?>">前の月</a>
<a href="index.php?<?php echo "yesterday=$r_date"; ?>">前の日</a>
<font size="4"><b><?php echo date('Y年m月d日',strtotime($r_date)); ?></b></font>
<a href="index.php?<?php echo "tomorrow=$r_date"; ?>"> 次の日</a>
<a href="index.php?<?php echo "next_month=$r_date"; ?>">次の月</a>
<table border="1">

<?php
	for ($i = 0; $i < $MAX_FLOOR; $i++) {
	echo '<tr>';
		//部屋番号表示
		for ($j = 1; $j <= $MAX_ROOM; $j++) {
			echo '<td>'.($MAX_FLOOR - $i).sprintf('%02d',$j).'</td>';
		}
	echo '</tr>';
	echo '<tr>';

    //予約が入っている部屋は×、入っていない部屋は○を表示
	    for ($k = intval(($MAX_FLOOR - $i).'01'); $k <= intval(($MAX_FLOOR - $i).$MAX_ROOM); $k++) {	
			if (in_array($k,$arr)) {
				echo '<td><a href="';
				//部屋番号と日付を渡す
				echo 'reservation_data.php?room='.$k.'&'.'r_date='.$r_date.'">×</a></td>';
			} else {
				echo '<td><a href="';
				echo 'reservation_new.php?room='.$k.'&'.'r_date='.$r_date.'">○</a></td>';
			}
		}
	}
	echo '</tr>';
?>
</table>
<br>
<form action="guest_search.php" method="post">
<input type="submit" name="button" value="ゲスト管理">
</form>

</td>
<td valign="top">
<br>
<table border="1">
<!-- 曜日ラベル作成 -->
<tr>
<th><font color="red">日</font></th>
<th>月</th>
<th>火</th>
<th>水</th>
<th>木</th>
<th>金</th>
<th><font color="blue">土</font></th>
</tr>

	<tr>
	<?php
	//カレンダー作成
	
	//現在の年月を取得
	$now = date("Ym",strtotime($r_date)); //201809 形式
	
	//年と月を代入
	$y = substr($now, 0 , 4);
	$m = substr($now, 4 , 2);
	
	//1日の曜日を取得
	$dsta = date("w", mktime(0,0,0, $m, 1, $y));
	
	//先頭から1日まで空白を表示
	for ($i = 1; $i <= $dsta; $i++) {
		echo '<td></td>';
	}
	
	//1日から月末まで表示
	$d = 1;
	while (checkdate($m, $d, $y)) {
		//クリックした日付になるようにリンクを挿入
		echo '<td><a href="?r_date='.$y.'-'.$m.'-'.$d.'">'.$d.'</td>';
		//土曜日だったら改行
		if (date("w", mktime(0, 0, 0, $m, $d, $y)) == 6) {
			echo '</tr>';
			
			//次の週がある時、新しい行を準備
			if (checkdate($m,$d + 1,$y)) {
				echo '<tr>';
			}
		}
		
		$d++;
		
	}
	
	//最後の週の土曜日まで空白を表示
	$dend = date("w", mktime(0, 0, 0, $m + 1, 0,$y));
	for ($i = 1; $i < 7 - $dend; $i++) {
		echo '<td></td>';
	}
	echo '</tr>';
	

	?>
</tr>
</table>

</td>
</tr>

</body>
</html>
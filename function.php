<?php

//未入力チェック
function not_entered_check($str) {
	if($str == ""){
		return false;
	} else {
		return true;
	}
}

//数字だけを入力しているかチェック
function number_check($str) {
	if(preg_match('/^[0-9]+$/', $str)){
		return true;
	} else {
		return false;
	}
}

//日付　規定の形式で入力されているかチェック  2018-10-12
function date_check($str) {
	if($str == date("Y-m-d", strtotime($str))){
		return true;
	} else {
		return false;
	}
}
?>
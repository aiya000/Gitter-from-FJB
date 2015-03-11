<?php
	require_once("../../MySQL_Helper.php");

	//登録情報の受け取りとデータベースへの登録
	$mailAddress = $_POST["RegistEmail"];
	if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mailAddress)) {
		echo '<script>';
		echo 'alert("メールアドレスが不正です");';
		echo 'location.href = "../";';
		echo '</script>';
		exit(1);
	}
	$userID = $_POST["RegistUserID"];
	if(preg_match("/[あ-んア-ン]/", $userID)){
		echo '<script>';
		echo 'alert("ユーザーIDに全角文字は使えません");';
		echo 'location.href = "../";';
		echo '</script>';
		exit(1);
	}
	$handleName = $_POST["RegistHandleName"];
	$password = $_POST["RegistPassword"];
	MySQL_Helper::s_registAccount($userID, $password, $mailAddress, $handleName);

	echo "登録しました。";

	//セッションの確認と登録
	session_start();
	$_SESSION["userID"] = $userID;
	$_SESSION["password"] = $password;

	//小休止
	sleep(2);

	//ページの遷移
	echo '<script>';
	echo 'location.href = "../../UserHome/";';
	echo '</script>';

?>

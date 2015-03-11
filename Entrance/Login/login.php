<?php
	require_once("../../MySQL_Helper.php");

	//空欄項目のチェック
	if(!isset($_POST["UserID"]) || !isset($_POST["Password"])){
		echo '<script>';
		echo 'alert("空欄項目があります");';
		echo 'location.href = "../";';
		echo '</script>';
		exit(1);
	}

	//ユーザーIDのチェック
	$userID = htmlspecialchars($_POST["UserID"]);

	//アルファベットと数字以外を弾く
	if(preg_match("/[^a-zA-Z0-9\-_\.]/s", $userID)){
		echo '<script>';
		echo 'alert("ユーザーIDに日本語は使えません");';
		echo 'location.href = "../";';
		echo '</script>';
		exit(1);
	}
	$password = $_POST["Password"];

	//マジカルログインチェック
	$loginSucceed = MySQL_Helper::s_loginAccount($userID, $password);
	if($loginSucceed){
		//セッションの登録
		session_start();
		$_SESSION["userID"] = $userID;
		$_SESSION["password"] = $password;

		//ページの遷移
		echo '<script>';
		echo 'location.href = "../../UserHome/";';
		echo '</script>';
	}else{
		//失敗の通達
		echo '<script>';
		echo 'alert("ログインに失敗しました");';
		echo 'location.href = "../";';
		echo '</script>';
		exit(1);
	}
?>

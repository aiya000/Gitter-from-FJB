<?php

require_once("../../MySQL_Helper.php");

	/* ログイン中のユーザーとユーザーのツイートを削除(退会処理) */
	session_start();
	$dbHelper = new MySQL_Helper($_SESSION["userID"]);
	$dbHelper->withdrawingUser();

	/* セッションを削除 */
	unset($_SESSION["userID"]);
	unset($_SESSION["password"]);

	/* 完了メッセージ */
	echo '<script> alert("退会しました\nまたのご利用をお待ちしております"); </script>';

	/* トップページに転送 */
	echo '<script>';
	echo 'location.href = "../../Entrance/";';
	echo '</script>';

?>

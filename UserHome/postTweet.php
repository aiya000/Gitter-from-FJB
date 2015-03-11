<?php
	require_once("../MySQL_Helper.php");

	//セッションのスタート
	session_start();

	//ツイートをDBにインサート
	$dbHelper = new MySQL_Helper($_SESSION["userID"]);
	$replaced = str_replace("\n", "<br>", $_GET["tw"]);
	$dbHelper->postTweet($replaced);
?>

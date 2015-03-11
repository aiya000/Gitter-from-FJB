<?php
	require_once("../MySQL_Helper.php");

	session_start();
	$userID = $_SESSION["userID"];

	//ツイートを読み込み
	$dbHelper = new MySQL_Helper($userID);
	$twArray = $dbHelper->fetchReplyTweet();
	$response = "";

	//取得したツイートを受け渡し用に整形
	if(!empty($twArray)) $response = $twArray[0];
	for($i=1; $i<count($twArray); $i++){
		// 最新のツイートが一番上に来るようにresponseへ格納
		$response = $response.";".$twArray[$i];
	}

	//結果を返す
	echo $response;
?>

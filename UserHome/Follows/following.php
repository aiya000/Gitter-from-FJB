<?php

require_once("../../MySQL_Helper.php");

//情報を受け取り
$userID = $_GET["userID"];
$followID = $_GET["followID"];

//$userIDで$followIDをフォローする
$dbHelper = new MySQL_Helper($userID);
$dbHelper->followingUser($followID);

//完了メッセージ
echo '<script>';
echo sprintf('alert("%sをフォローしました");', $followID);
echo sprintf('location.href = "./index.html?mode=%s"', $_GET["currentMode"]); //ページをリロード
echo '</script>';

?>

<?php
	/* セッションを削除 */
	session_start();
	unset($_SESSION["userID"]);
	unset($_SESSION["password"]);

	/* トップへ移動 */
	echo '<script>';
	echo 'location.href = "../Entrance/";';
	echo '</script>';
?>

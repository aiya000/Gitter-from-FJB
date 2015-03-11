<?php

/* -- クラス内容 --
 *	根底メソッド
 *	静的根底メソッド
 *	共用メソッド - 共用メソッドは多箇所から利用される
 *		ThisUserメソッド
 *		Tweetメソッド
 *		Followsメソッド
 *	特化メソッド - 特化メソッドは一箇所から利用される
 *		Loginメソッド
 *		Registメソッド
 *		Withdrawメソッド
 *		OtherUserメソッド
 *		Tweetメソッド
 *		Followsメソッド
 */

class MySQL_Helper{

	/************ 根底メソッド ************
	*/

	/***** メンバ定数 *****/

	const ACCOUNT_TABLE = "Account_Table";
	const TWEET_TABLE = "Tweet_Table";
	const FOLLOW_TABLE = "Follow_Table";

	/***** メンバ変数 *****/

	protected $userID;
	protected $debug = false;

	/**********************/

	/* ユーザーIDを受け取り */
	/* $debugFlagがtrueならデバッグモード */
	public function __construct($userID, $debugFlag=false){
		$this->userID = $userID;
		$this->debug = $debugFlag;
	}

	/***** MySQLに接続 *****/
	protected function connect_mysql(){
		//$link = mysql_connect("localhost", "php_test", "passwd");
		$link = mysql_connect("localhost", "Gitter_admin", "Gpasswd000");
		if(!$link){
			print "MySQLへの接続に失敗しました<br>".mysql_error();
			exit(1);
		}
		if($this->debug) print "MySQLへの接続に成功しました<br>";

		//デフォルトキャラセット
		mysql_set_charset("utf8");
		return $link;
	}

	/***** データベースへの接続 *****/
	protected function connect_db($link){
		//$db_select = mysql_select_db("php_test", $link);
		$db_select = mysql_select_db("Gitter", $link);
		if(!$db_select){
			print "データベースの接続に失敗しました<br>".mysql_error();
			exit(1);
		}
		if($this->debug) print "データベースへの接続に成功しました<br>";
		return $db_select;
	}

	/***** MySQLから切断 *****/
	protected function close($link){
		if( mysql_close($link) ){
			if($this->debug) print "MySQLから切断しました<br>";
		}else{
			exit("MySQLからの切断に失敗しました<br>".mysql_error());
		}
	}


	/***** エンコードを変更(できるかな？) *****/
	public function setEncoding($enc){
		//指定されたエンコードに変更(成功すればtrueが返される)
		if(!mysql_set_charset($enc)){
			exit("エンコードの設定に失敗しました");
		}
		if($this->debug) print("エンコードを再設定しました<br>");
	}

	/***** 全ての文字をエスケープ(インジェクション対策) *****/
	public function measureInjection($string){
		$string = mysql_real_escape_string($string);
		if(!$string){
			exit("SQLインジェクション対策に問題が発生しました");
		}
		if($this->debug) print("文字列「".$string."」をエスケープしました<br>");
		return $string;
	}


	/************ 静的根底メソッド ************
	*/

	/***** メンバ変数 *****/

	protected static $s_debug = false;

	/**********************/

	/* $debugFlagがtrueならデバッグモード */
	public static function s_setDebug($debugFlag){
		self::$s_debug = $debugFlag;
	}


	/***** MySQLに接続 *****/
	protected static function s_connect_mysql(){
		$link = mysql_connect("localhost", "Gitter_admin", "Gpasswd000");
		if(!$link){
			print "MySQLへの接続に失敗しました<br>".mysql_error();
			exit(1);
		}
		if(self::$s_debug) print "MySQLへの接続に成功しました<br>";

		//デフォルトキャラセット
		mysql_set_charset("utf8");
		return $link;
	}


	/***** データベースへの接続 *****/
	protected static function s_connect_db($link){
		$db_select = mysql_select_db("Gitter", $link);
		if(!$db_select){
			print "データベースの接続に失敗しました<br>".mysql_error();
			exit(1);
		}
		if(self::$s_debug) print "データベースへの接続に成功しました<br>";
		return $db_select;
	}


	/***** MySQLから切断 *****/
	protected static function s_close($link){
		if( mysql_close($link) ){
			if(self::$s_debug) print "MySQLから切断しました<br>";
		}else{
			exit("MySQLからの切断に失敗しました<br>".mysql_error());
		}
	}

	/***** エンコードを変更(できるかな？) *****/
	public static function s_setEncoding($enc){
		//指定されたエンコードに変更(成功すればtrueが返される)
		if(!mysql_set_charset($enc)){
			exit("エンコードの設定に失敗しました");
		}
		if(self::$s_debug) print("エンコードを再設定しました<br>");
	}

	/***** 全ての文字をエスケープ(インジェクション対策) *****/
	public static function s_measureInjection($detail){
		$detail = mysql_real_escape_string($detail);
		if(!$detail){
			exit("SQLインジェクション対策に問題が発生しました");
		}
		if(self::$s_debug) print("文字列「".$detail."」をエスケープしました<br>");
		return $detail;
	}


	/************ 共用メソッド ************
	*/

	/********** ThisUserメソッド ****/

	/* 自分のハンドルネームを返す */
	public function getHandleName(){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//クエリを発行
		$sql = "select handleName from ".self::ACCOUNT_TABLE." where userID = '".$this->userID."'";
		$result = mysql_query($sql);
		if(!$result){
			exit("ハンドルネームの取得に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("ハンドルネームの取得に成功しました<br>");

		//ハンドルネームを取得
		$row = mysql_fetch_assoc($result);
		$handleName = $row["handleName"];

		//MySQLから切断
		$this->close($link);

		//取得できたハンドルネームを返す
		return $handleName;
	}

	/********** Tweetメソッド ****/

	/***** 内容をツイート*****/
	public function postTweet($detail){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//SQLインジェクション対策
		$detail = $this->measureInjection($detail);

		//データのインサート
		$sql = sprintf(
				"insert into %s(userID, detail, twTime) values('%s', '%s', now());",
				self::TWEET_TABLE, $this->userID, $detail);
		$result = mysql_query($sql);
		if(!$result){
			exit("INSERTに失敗しました<br>".mysql_error());
		}
		if($this->debug) print("INSERTに成功しました<br>");

		//MySQLから切断
		$this->close($link);
	}

	/********** Followsメソッド ****/

	/* 指定ユーザーを$this->userIDがフォローする */
	public function followingUser($userID){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//エンコードの変更
		$this->setEncoding("utf8");

		//$this->userIDで引数の$userIDをフォロー
		$followsArray = array();
		$sql = sprintf("INSERT INTO ".self::FOLLOW_TABLE.
			" values('%s', '%s')", $this->userID, $userID);
		$result = mysql_query($sql);
		if(!$result){
			exit("フォローに失敗しました<br>".mysql_error());
		}
		if($this->debug) print("フォローに成功しました<br>");

		//MySQLから切断
		$this->close($link);
	}

	/* 指定ユーザーをフォロー済みか返す */
	public function isFollowed($userID){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//エンコードの変更
		$this->setEncoding("utf8");

		//フォロ済みアカウントの取得
		$followsArray = array();
		$sql = "SELECT followID AS valid FROM ".self::FOLLOW_TABLE.
			" WHERE userID = '".$this->userID."'".
			" AND followID = '".$userID."'";
		$result = mysql_query($sql);
		if(!$result){
			exit("フォロー済み判別に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("フォロー済み判別に成功しました<br>");

		//フォロー済みであるかを取得
		$row = mysql_fetch_assoc($result);
		$valid = $row["valid"];

		//MySQLから切断
		$this->close($link);

		//ユーザーがフォロー済みか返す
		if(!is_null($valid)){
			return true;
		}else{
			return false;
		}
	}
	

	/************ 特化メソッド ************
	*/

	/********** Loginメソッド ****/

	/***** アカウント認証してログイン *****/
	public static function s_loginAccount($userID, $password){
		//DBに接続
		$link = self::s_connect_mysql();
		$db_select = self::s_connect_db($link);

		//SQLインジェクション対策
		$userID = self::s_measureInjection($userID);
		$password = self::s_measureInjection($password);

		//ユーザーの認証
		$sql = "SELECT userID FROM ".self::ACCOUNT_TABLE.
			" WHERE userID='".$userID."'".
			" AND password='".$password."'";
		$result = mysql_query($sql);
		if(!$result){
			exit("ユーザーの認証情報取得に失敗しました<br>".mysql_error());
		}
		if(self::$s_debug) print("ユーザーの認証情報取得に成功しました<br>");

		//認証情報の受け取り
		$row = mysql_fetch_assoc($result);
		$checkLogin = $row["userID"];

		//MySQLから切断
		self::s_close($link);

		if(self::$s_debug) print("\$checkLogin is '".$checkLogin."'<br>");

		//ユーザーの認証
		if( isset($checkLogin) ){
			if(self::$s_debug) print("ユーザーの認証に成功しました");
			return true;
		}else{
			if(self::$s_debug) print("ユーザーの認証に失敗しました");
			return false;
		}
	}
	

	/********** Registメソッド ****/

	/***** アカウントを登録 *****/
	public static function s_registAccount($userID, $password, $mailAddress ,$handleName){
		//DBに接続
		$link = self::s_connect_mysql();
		$db_select = self::s_connect_db($link);

		//SQLインジェクション対策
		$userID = self::s_measureInjection($userID);
		$password = self::s_measureInjection($password);
		$mailAddress = self::s_measureInjection($mailAddress);
		$handleName = self::s_measureInjection($handleName);

		//データの登録
		$sql = sprintf(
				"insert into %s values('%s', '%s', '%s', '%s');",
				self::ACCOUNT_TABLE, $userID, $password, $mailAddress, $handleName);
		$result = mysql_query($sql);
		if(!$result){
			exit("ユーザーの登録に失敗しました<br>".mysql_error());
		}
		if(self::$s_debug) print("ユーザーの登録に成功しました<br>");

		//MySQLから切断
		self::s_close($link);
	}


	/********** Withdrawメソッド ****/

	/***** 自分の退会処理 *****/
	public function withdrawingUser(){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//ユーザーのツイートを削除
		$tweetdel = "DELETE FROM ".self::TWEET_TABLE.
			" WHERE userID='".$this->userID."'";
		$tdResult = mysql_query($tweetdel);
		if(!$tdResult){
			exit("ユーザーのツイート履歴の削除に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("ユーザーのツイート履歴の削除に成功しました<br>");

		//ユーザーのフォロー状態を削除
		$tweetdel = "DELETE FROM ".self::FOLLOW_TABLE.
			" WHERE userID='".$this.userID."'";
		$tdResult = mysql_query($tweetdel);
		if(!$tdResult){
			exit("ユーザーのフォロー履歴の削除に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("ユーザーのフォロー履歴の削除に成功しました<br>");

		//ユーザーの削除
		$userdel = "DELETE FROM ".self::ACCOUNT_TABLE.
			" WHERE userID='".$this->userID."'";
		$udResult = mysql_query($userdel);
		if(!$udResult){
			exit("ユーザーの削除に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("ユーザーの削除に成功しました<br>");
		
		//MySQLから切断
		$this->close($link);
	}


	/********** OtherUserメソッド ****/

	/* 検索ワードに合致したユーザーを返す */
	public function getSearchedUser($word){
		//DBに接続
		$link = $this->connect_mysql();
		$this->connect_db($link);

		//全ユーザーの選択
		$allUserArray = array();
		$sql = "SELECT handleName FROM ".self::ACCOUNT_TABLE;
		$result = mysql_query($sql);
		if(!$result) exit("DB情報の取得に失敗しました");

		//選択されたクエリの取得
		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			// デザインを適用する準備を整えつつ整形
			$allUserArray[$i] = $row["handleName"];
		}

		//デバッグ
		if($this->debug){
			print("全ユーザーの取得に成功しました : <br>");
			print_r($allUserArray);
		}

		//MySQLから切断
		$this->close($link);

		/* 全ユーザーの中から検索ワードに合致するユーザー表示名を選択する */
		$userArray = array();
		$selectedCount = 0;
		for($i=0; $i<count($allUserArray); $i++){
			if(preg_match("/".$word."/", $allUserArray[$i])){
				$userArray[$selectedCount++] = $allUserArray[$i];
			}
		}
		if($this->debug) print( ($selectedCount-1)."件のユーザーが選択されました" );

		//選択されたユーザーを返す
		return $userArray;
	}

	//指定ハンドルネームのユーザーIDを返す
	public function handleNameToUserID($handleName){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//クエリを発行
		$sql = "SELECT userID FROM ".self::ACCOUNT_TABLE.
			" WHERE handleName = '".$handleName."'";
		$result = mysql_query($sql);
		if(!$result){
			exit("ユーザーIDの取得に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("ユーザーIDの取得に成功しました<br>");

		//ハンドルネームを取得
		$row = mysql_fetch_assoc($result);
		$userID = $row["userID"];

		//MySQLから切断
		$this->close($link);

		//取得できたユーザーIDを返す
		return $userID;
	}


	/********** Tweetメソッド ****/

	/* クエリ配列を返す*/
	public function fetchTweet(){
		//DBに接続
		$link = $this->connect_mysql();
		$this->connect_db($link);

		//クエリの発行
		$tweetArray = array();
		$sql = "SELECT twTime, detail FROM Tweet_Table".
			" WHERE userID='".$this->userID."' ORDER BY twID DESC";
		$result = mysql_query($sql);
		if(!$result) exit("DB情報の取得に失敗しました");

		//選択されたクエリの取得
		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			// デザインを適用する準備を整えつつ整形
			$tmpDetail = '<div class="detail">'.$row["detail"].'</div>';
			$tmpTime = '<div class="time">'.$row["twTime"].'</div>';
			$tweetArray[$i] = $tmpTime.",".$tmpDetail;
		}

		//デバッグ
		if($this->debug){
			print("クエリの発行に成功しました : <br>");
			print_r($tweetArray);
		}

		//MySQLから切断
		$this->close($link);

		return $tweetArray;
		/* TODO:fetchTweet ツイートを20件取得、
		 * fetchNext()でもう20件取得、
		 * 等、考える必要があるかもしれない(レスポンス問題)
		*/
	}

	/* リプライツイートのクエリ配列を返す */
	public function fetchReplyTweet(){
		//DBに接続
		$link = $this->connect_mysql();
		$this->connect_db($link);

		//クエリの発行
		$tweetArray = array();
		$sql = "SELECT userID, twTime, detail FROM Tweet_Table".
			" WHERE userID='".$this->userID."'".
			" AND detail LIKE '%@".$this->userID."%'".
			" ORDER BY twID DESC";
		$result = mysql_query($sql);
		if(!$result) exit("リプライ情報の取得に失敗しました");

		//選択されたクエリの取得
		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			// デザインを適用する準備を整えつつ整形
			$tmpReplyUserID = '<div class="userID">'.$row["userID"].'</div>';
			$tmpDetail = '<div class="detail">'.$row["detail"].'</div>';
			$tmpTime = '<div class="time">'.$row["twTime"].'</div>';
			$tweetArray[$i] = $tmpReplyUserID.",".$tmpTime.",".$tmpDetail;
		}

		//デバッグ
		if($this->debug){
			print("リプライクエリの発行に成功しました : <br>");
			print_r($tweetArray);
		}

		//MySQLから切断
		$this->close($link);

		return $tweetArray;
		/* TODO: fetchReplyTweet(); ツイートを20件取得、
		 * TODO: fetchNext()でもう20件取得、
		 * TODO: 等、考える必要があるかもしれない(レスポンス問題)
		 * TODO: というかこれ、ただのやっつけです。リプライテーブルに分けるとかしてません。
		 */
	}

	/* TL取得用 */
	public function fetchTimeline(){
		//DBに接続
		$link = $this->connect_mysql();
		$this->connect_db($link);

		//フォロー済みアカウント(ツイート取得の標的)
		$followArray = array();
		$sql = "SELECT followID FROM ".self::FOLLOW_TABLE.
			" WHERE userID='".$this->userID."'";
		$result = mysql_query($sql);
		if(!$result) exit("TL用のフォロー済みアカウントの取得に失敗しました");

		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			$followArray[$i] = $row["followID"];
		}

		//条件文字列の作成
		$terms = "";
		for($i=0; $i<count($followArray); $i++){
			$terms .= sprintf(" OR userID='%s'", $followArray[$i]);
		}

		//TLの生成
		$timelineArray = array();
		$sql = "SELECT userID, detail, twTime FROM ".self::TWEET_TABLE.
			" WHERE userID='".$this->userID."'".$terms." ORDER BY twID desc";
		$result = mysql_query($sql);
		if(!$result) exit("TLの取得に失敗しました");

		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			// デザインを適用する準備を整えつつ整形
			$tmpUserID = '<div class="userID">'.$row["userID"].'</div>';
			$tmpDetail = '<div class="detail">'.$row["detail"].'</div>';
			$tmpTime = '<div class="time">'.$row["twTime"].'</div>';
			$timelineArray[$i] = $tmpUserID.",".$tmpTime.",".$tmpDetail;
		}

		//デバッグ
		if($this->debug){
			print("クエリの発行に成功しました : <br>");
			print_r($timelineArray);
		}

		//MySQLから切断
		$this->close($link);

		return $timelineArray;
		/* TODO:fetchTimeline ; ツイートを20件取得、
		 * TODO: fetchNext()でもう20件取得、
		 * TODO: 等、考える必要があるかもしれない(レスポンス問題)
		*/
	}

	/********** Followsメソッド ****/

	/* $this->userIDのフォロワーのユーザーIDを返す */
	public function fetchFollowersID(){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//エンコードの変更
		$this->setEncoding("utf8");

		//フォロ済みアカウントの取得
		$followerArray = array();
		$sql = "select userID from ".self::FOLLOW_TABLE.
			" where followID = '".$this->userID."'";
		$result = mysql_query($sql);
		if(!$result){
			exit("フォロワーアカウントの取得に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("フォロワーアカウントの取得に成功しました<br>");

		//選択されたクエリの取得
		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			$followerArray[$i] = $row["userID"];
		}

		//デバッグ
		if($this->debug){
			print("フォロー済みアカウントのクエリ発行に成功しました : <br>");
			print_r($followerArray);
		}

		//MySQLから切断
		$this->close($link);

		//フォロ済みアカウントIDを戻す
		return $followerArray;
	}

	/* $this->userIDがフォローしているユーザーのIDを返す */
	public function fetchFollowsID(){
		//DBに接続
		$link = $this->connect_mysql();
		$db_select = $this->connect_db($link);

		//エンコードの変更
		$this->setEncoding("utf8");

		//フォロ済みアカウントの取得
		$followsArray = array();
		$sql = "select followID from ".self::FOLLOW_TABLE.
			" where userID = '".$this->userID."'";
		$result = mysql_query($sql);
		if(!$result){
			exit("フォロー済みアカウントの取得に失敗しました<br>".mysql_error());
		}
		if($this->debug) print("フォロー済みアカウントの取得に成功しました<br>");

		//選択されたクエリの取得
		for($i=0; $row=mysql_fetch_assoc($result); $i++){
			$followsArray[$i] = $row["followID"];
		}

		//デバッグ
		if($this->debug){
			print("フォロー済みアカウントのクエリ発行に成功しました : <br>");
			print_r($followsArray);
		}

		//MySQLから切断
		$this->close($link);

		//フォロ済みアカウントIDを戻す
		return $followsArray;
	}
}

?>

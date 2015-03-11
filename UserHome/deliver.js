
/* ツイートを指定エリアに表示 */
function load(userID, flag){

	//ajaxリクエスト用オブジェクト
	var xmlhttp = getHttpRequest();

	//リクエストopen時に行うメソッドを設定
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			
			//phpから整形済みツイートリストを受け取り
			var tweets = xmlhttp.responseText;
			//ツイートが一件もないときの対処
			if(tweets == ""){
				document.getElementById("tweetArea").innerHTML = "ツイートはありません<br>";
				return;
			}

			//最終出力
			var tweetList = formatToArray(tweets);
			document.getElementById("tweetArea").innerHTML = ( flag==0 ? textToFormatForHome(tweetList) : textToFormatForTl(tweetList) );
		}
	}

	//PHPと連携
	xmlhttp.open("GET", "fetchTweet.php?userID="+userID+"&flag="+flag, true);
	xmlhttp.send();
}

//適したhttpRequestオブジェクトを戻す
function getHttpRequest(){
	var xmlhttp;
	if(window.XMLHttpRequest){//IE以外
		xmlhttp = new XMLHttpRequest();
	}else{//IE用
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}


/* 整形済みツイートリストを配列に変換 */
function formatToArray(formatList){
	return formatList.split(";");
}

/* ツイートリストから最終出力フォーマットに変換(ユーザーホーム用) */
function textToFormatForHome(tweetList){
	var list = "";
	for(var i=0; i<tweetList.length; i++){
		//情報を取得
		var tweetInfo = tweetList[i].split(",");
		var time = tweetInfo[0];
		var tweet = tweetInfo[1];
		
		//整形(すべてdivタグで囲むので改行タグは付けない)
		list += '<hr>';
		list += '<div class="tweet">';
		list += time;
		list += tweet;
		list += '</div>';
	}
	return list;
}

/* ツイートリストから最終出力フォーマットに変換(タイムライン用) */
function textToFormatForTl(tweetList){
	var list = "";
	for(var i=0; i<tweetList.length; i++){
		//情報を取得
		var tweetInfo = tweetList[i].split(",");
		var user = tweetInfo[0];
		var time = tweetInfo[1];
		var tweet = tweetInfo[2];
		
		//整形(すべてdivタグで囲むので改行タグは付けない)
		list += '<hr>';
		list += '<div class="tweet">';
		list += user;
		list += time;
		list += tweet;
		list += '</div>';
	}
	return list;
}

/************************************/


/* 指定テキストをツイート */
function post(){

	//ajaxリクエスト用オブジェクト
	var xmlhttp = getHttpRequest();
	
	//ツイート内容があるテキストエリアの改行をreplaceAll
	var detail = (document.tweetHome.detail.value).replace(/\n/g, "<br>");
	if(detail.length < 1){
		return;
	}

	//リクエストopen時に行うメソッドを設定
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			// TODO: ツイートしました的メッセージ	

			//テキストエリアをクリア
			document.tweetHome.detail.value = "";
		}
	}

	//PHPと連携
	xmlhttp.open("POST", "postTweet.php?tw="+detail, true);
	xmlhttp.send();
}


/************************************/


/* 退会処理実行ファイルに渡すだけ */
function withdrawConfirm(){
	//本当に退会していいか確認
	var ans = confirm("ユーザーデータが全て削除されます\n本当に退会してもよろしいですか？");
	if(ans){
		//退会処理
		location.href = "./Withdrawing/withdraw.php";
	}else{
		//ユーザーホームへ戻る
		location.href = "../UserHome/";
	}
}

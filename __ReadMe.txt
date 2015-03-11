
  じゃじゃーん、ReadMeと思わせておいてドキュメントでーす。


{- ------------------------------------------------------ -}
処理系を担当するファイルのツリーです。
{- ------------------------------------------------------ -}
現在ReplyHome以下(リプライ画面とリプライ機能)はハリボテですので、余分なものが多分に含まれています。
もし編集する方がいれば、おそらく作り直した方がはやいです。
{- ------------------------------------------------------ -}
各ディレクトリにあるThisPage.txtにより、そのディレクトリ内のファイルの役割が説明されています。
{- ------------------------------------------------------ -}
/(DocumentRoot)
 |   _ MySQL_Helper.php
 |   _ HogeSQL_InterfaceHelper.php
 |
 |-- /UserHome
 |    |   _ index.html
 |    |   _ logout.php
 |    |   _ deliver.js
 |    |   _ postTweet.php
 |    |   _ ThisPage.txt
 |    |   _ config.html
 |    |
 |    |-- /UserHome/Withdrawing
 |    |      _ withdraw.php
 |    |      _ ThisPage.txt
 |    |
 |    |-- /UserHome/Follows
 |    |      _ ThisPage.txt
 |    |      _ following.php
 |    |      _ index.html
 |    |
 |    |-- /UserHome/UserSearch
 |    |      _ index.html
 |    |      _ ThisPage.txt
 |    |      _ following.php
 |   
 |-- /ReplyHome
 |    |   _ logout.php
 |    |   _ kuzu.css
 |    |   _ index.html
 |    |   _ fetchTweet.php
 |    |   _ userhome.css
 |    |   _ ThisPage.txt
 |    |   _ deliver.js
 |    |   _ ritsuko.jpg
 |    |   _ postTweet.php
 |    |
 |    |-- /ReplyHome/Entrance
 |    |      _ index.html
 |    |
 |    |-- /ReplyHome/Regist
 |    |      _ regist.php
 |    |      _ ThisPage.txt
 |    |
 |    |-- /ReplyHome/Login
 |    |      _ ThisPage.txt
 |    |      _ login.php
{- ------------------------------------------------------ -}

(* ------------------------------------------------------ *)
各ディレクトリの構成は
 「実際の画面遷移順」
と同期してあります。

例えば退会画面(Withdrawing)はユーザホーム画面の先にあるので、
/UserHome配下にディレクトリを配置しています。
(* ------------------------------------------------------ *)

/* ------------------------------------------------------ */
データベース操作はドキュメントルート直下にある
  ・HogeSQL_InterfaceHelper.php
  ・MySQL_Helper.php
にて集約されたメソッド群を各スクリプトから使用します。
/* ------------------------------------------------------ */
「HogeSQL_InterfaceHelper.php」は
複種類のデータベースに対応するためのインターフェースクラスファイルです。
現在未構築です。(使われていません)
/* ------------------------------------------------------ */
「MySQL_Helper.php」はDBに対する操作を集約したファイルです。
このファイルに全ての操作をまとめた理由としては、
  ・機能の重複を省くため
  ・HogeSQL_InterfaceHelperでの管理法を適用するため
です。
もちろんインスタンス化する場合はメモリの確保容量が大きくなりますので、負担は大きくなります。
/* ------------------------------------------------------ */

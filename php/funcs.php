<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続関数：db_conn()
function db_conn(){
    try {
        $db_name = "flowersknives"; //デプロイ"flowersknives_member" 開発"flowersknives"
        $db_id = "root"; //デプロイ"flowersknives_member" 開発"root"
        $db_pw = ""; //XAMPPは空白、MAMPPはroot。デプロイの時は"member"に変更
        $db_host = "localhost"; //デプロイ"mysql80.flowersknives.sakura.ne.jp" 開発"localhost"
        //$pdo = new PDO('mysql:dbname=flowersknives;charset=utf8;host=localhost','root','');
        return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB_CONECT:'.$e->getMessage()); 
    }
}

//SQLエラー関数：sql_error($stmt)
function sql_error($stmt){
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}

//リダイレクト関数: redirect($file_name)
function redirect($file_name){
    header("Location: ".$file_name);
    exit();
}

// ログインチェック関数
function loginCheck() {
    session_start(); // セッションの開始
    if (!isset($_SESSION["lid"])) { // セッションにログイン情報がない場合
        header("Location: ../login.html"); // ログインページにリダイレクト
        exit(); // スクリプトを停止
    }
}

//SessionCheck(スケルトン)
function sschk(){
    //issetはセットしている場合で!を追加したらセットしていない場合になる。||はandです
    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
        exit("Login Error");
      }else{
        session_regenerate_id(true); //SESSION KEYを入れ替える
        $_SESSION["chk_ssid"] = session_id();
      }
}
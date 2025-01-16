<?php
//1. POSTデータ取得
$name     = $_POST["name"];
$lid      = $_POST["lid"];
$lpw      = $_POST["lpw"];
$tel      = $_POST["tel"];

//2. DB接続します
include("./funcs.php"); //外部ファイル読み込み
$pdo = db_conn();

//３．データ登録SQL作成
//INSERT文準備
$sql = "INSERT INTO member(name,lid,lpw,tel)VALUES(:name,:lid,:lpw,:tel);";
$stmt = $pdo->prepare($sql);

//各パラメータ
$stmt->bindValue(':name',   $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':lid',    $lid,    PDO::PARAM_STR);
$stmt->bindValue(':lpw',    password_hash($_POST['lpw'], PASSWORD_DEFAULT), PDO::PARAM_STR);
$stmt->bindValue(':tel',    $tel,    PDO::PARAM_STR);
$status = $stmt->execute(); //true or false

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("SQL_ERROR:".$error[2]);
}else{
  //５．thanks.phpへリダイレクト
  header("Location: ../login.html");
  exit();
}
?>
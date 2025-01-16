<?php
session_start();
include("funcs.php");

// ログインチェック
sschk();

// DB接続
$pdo = db_conn();

//1. POSTデータ取得
$sender_id = $_SESSION['id'] ?? null; // ログイン中のユーザーID
$sender_name = $_SESSION['name'] ?? null; // ログイン中のユーザー名
$receiver_id = $_POST['receiver_id'] ?? null; // 受取者ID
$letterimg = ""; // 画像パス

// データが揃っているか確認
if (!$sender_id || !$receiver_id || !$sender_name) {
    exit('送信者または受取者の情報が不足しています。');
}

// ファイルアップロード処理
if (isset($_FILES['letterimg']) && $_FILES['letterimg']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $file_name = uniqid() . '_' . $_FILES['letterimg']['name'];
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['letterimg']['tmp_name'], $file_path)) {
        $letterimg = $file_path;
    } else {
        exit('画像のアップロードに失敗しました。');
    }
} else {
    exit('画像が選択されていません。');
}

//３．データ登録SQL作成
$sql = "INSERT INTO letterbox (sender_id, receiver_id, letterimg, sender_name) VALUES (:sender_id, :receiver_id, :letterimg, :sender_name)";
$stmt = $pdo->prepare($sql);

//各パラメータ
$stmt->bindValue(':sender_id',   $sender_id,   PDO::PARAM_INT);
$stmt->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
$stmt->bindValue(':letterimg',   $letterimg,   PDO::PARAM_STR);
$stmt->bindValue(':sender_name', $sender_name, PDO::PARAM_STR);


$status = $stmt->execute(); //true or false

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("SQL_ERROR:".$error[2]);
}else{
  //５．thanks.phpへリダイレクト
  header("Location: ./sendbox.php");
  exit();
}
?>
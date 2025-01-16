<?php
//0.  SESSION開始！
session_start();

//1.  DB接続します
include("./funcs.php"); //外部のファイルを読み込む

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

// ログイン中のユーザーID
$sender_id = $_SESSION['id'];

//２．データ登録SQL作成
$pdo = db_conn(); //読み込んだファイルの中のdb_connを実行する

$sql = "
    SELECT 
        letterbox.receiver_id, 
        letterbox.letterimg, 
        letterbox.send_date, 
        member.name AS receiver_name
    FROM 
        letterbox
    JOIN 
        member
    ON 
        letterbox.receiver_id = member.id
    WHERE 
        letterbox.sender_id = :sender_id
    ORDER BY 
        letterbox.send_date DESC
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sender_id', $sender_id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$values = ""; 
if($status==false) {
    sql_error($stmt);
} else {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//全データ取得
$values = $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
//JSONに値を渡す場合に使う
$json = json_encode($values,JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/hp/favicon.png">
    <link rel="stylesheet" href="../css/box.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol&family=Yusei+Magic&family=Zen+Kaku+Gothic+New&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&family=Noto+Serif+JP:wght@200;300;400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&display=swap" rel="stylesheet">
    <title>はなやいば - 送信BOX</title>
</head>
<body>
    <header>
        <div id="username"><?=$_SESSION["name"]?>さん</div>
        <div id="logo"><a href="./buddy.php"><img src="../img/main/logo_black.png" style="height: 70px;"></a></div>
        <div id="logout"><a href="logout.php"><img src="../img/letter/logout.png" style="height: 25px; margin-right: 5px;">ログアウト</a></div>
    </header>
    
    <main>
    <h4>送信済のお手紙</h4>
        <?php if (empty($results)): ?>
            <h5>送信した手紙はまだありません。</h5>
        <?php else: ?>
            <div class="letters">
                <?php foreach ($results as $row): ?>
                <div class="letterview">
                    <a href="<?= htmlspecialchars($row['letterimg'], ENT_QUOTES, 'UTF-8') ?>" target="_blank"><img src="<?= htmlspecialchars($row['letterimg'], ENT_QUOTES, 'UTF-8') ?>" class="letterimg"></a>
                    <p style="font-size: 15px;">送信先: <?= htmlspecialchars($row['receiver_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p style="color: #666; font-size: 12px; margin: 5px 0;">送信日時: <?= htmlspecialchars($row['send_date'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <a class="btn" href="./write.php" ><img src="../img/main/pen.png">他の手紙を送る</a>
    </main>

    <footer>
        <hr size="1">
        <div class="copyright">
            <p>© FlowersKnives, Inc. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
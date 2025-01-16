<?php
//0.  SESSION開始！
session_start();

//1.  DB接続します
include("./funcs.php"); //外部のファイルを読み込む

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

//２．データ登録SQL作成
$pdo = db_conn(); //読み込んだファイルの中のdb_connを実行する
$sql = "SELECT * FROM member";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute(); //true or false

//３．データ表示
$values = ""; 
if($status==false) {
    sql_error($stmt);
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
    <link rel="stylesheet" href="../css/buddy.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol&family=Yusei+Magic&family=Zen+Kaku+Gothic+New&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&family=Noto+Serif+JP:wght@200;300;400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&display=swap" rel="stylesheet">
    <title>はなやいば - バディ</title>
</head>
<body>
    <header>
        <div id="username"><?=$_SESSION["name"]?>さん</div>
        <div id="logo"><img src="../img/main/logo_black.png" style="height: 70px;"></div>
        <div id="logout"><a href="logout.php"><img src="../img/letter/logout.png" style="height: 25px; margin-right: 5px;">ログアウト</a></div>
    </header>

    <main>

        <div style="margin-top: 50px;">
            <ul class="member">
                <li>
                    <a href="../blog_riku.html" target="_blank"><img src="../img/hp/riku.jpg" id="riku_icon" class="member_icon"></a>
                    <h5>水木 凛空</h5>
                    <p>ファウンダー</p>
                </li>
                <li>
                    <img src="../img/hp/sample_m.png" id="hs_icon" class="member_icon">
                    <h5>H.S</h5>
                    <p>アーティスト</p>
                </li>
                <li>
                    <img src="../img/hp/sample_m.png" id="ys_icon" class="member_icon">
                    <h5>Y.S</h5>
                    <p>俳優</p>
                </li>
                <li>
                    <img src="../img/hp/sample_m.png" id="is_icon" class="member_icon">
                    <h5>I.S</h5>
                    <p>エンターテイナー</p>
                </li>
                <li>
                    <img src="../img/hp/sample_m.png" id="ik_icon" class="member_icon">
                    <h5>I.K</h5>
                    <p>作家</p>
                </li>
            </ul>
        </div>

        <div class="towrite">
            <div class="write">
                <img src="../img/main/writewoman.png">
            </div>
            <div class="write">
                <div class="writebtn">
                    <a class="btn" href="./write.php" ><img src="../img/main/pen.png">手紙を送る</a>
                </div>
            </div>
        </div>

    </main>

    <footer>
        <hr size="1">
        <div class="copyright">
            <p>© FlowersKnives, Inc. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
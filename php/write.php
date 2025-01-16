<?php
//0.  SESSION開始！
session_start();

//1.  DB接続します
include("./funcs.php"); //外部のファイルを読み込む

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

//２．データ登録SQL作成
$pdo = db_conn(); //読み込んだファイルの中のdb_connを実行する
$sql = "SELECT * FROM letterbox";
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
    <link rel="stylesheet" href="../css/write.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol&family=Yusei+Magic&family=Zen+Kaku+Gothic+New&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&family=Noto+Serif+JP:wght@200;300;400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100&display=swap" rel="stylesheet">
    <title>はなやいば - 手紙を書く</title>
</head>
<body>
    <header>
        <div id="username"><?=$_SESSION["name"]?>さん</div>
        <div id="logo"><a href="./buddy.php"><img src="../img/main/logo_black.png" style="height: 70px;"></a></div>
        <div id="logout"><a href="logout.php"><img src="../img/letter/logout.png" style="height: 25px; margin-right: 5px;">ログアウト</a></div>
    </header>

    <main>
        <div id="box">
            <div id="left">
                <div>
                    <a href="./sendbox.php" id="letterboxbtn"><img src="../img/letter/done.png" style="width: 18px; margin-right: 10px;">送信BOX</a>
                </div>
                <hr>
                <h5>送信先を選ぶ</h5>
                <h5>手紙をアップする</h5>
                <h5>手紙を送る</h5>
            </div>

            <div id="right">
                <form action="./insert_send.php" method="POST" enctype="multipart/form-data">
                    <p class="title">送信先を選ぶ</p>
                    <ul class="member">
                        <li>
                            <label for="mizuki">
                                <img src="../img/hp/riku.jpg" id="riku_icon" class="member_icon">
                                <h5>水木 凛空</h5>
                                <p>ファウンダー</p>
                            </label>
                            <input id="mizuki" type="radio" name="receiver_id" value="1" checked>
                        </li>
                        <li>
                            <label for="hs">
                                <img src="../img/hp/sample_m.png" id="hs_icon" class="member_icon">
                                <h5>H.S</h5>
                                <p>アーティスト</p>
                            </label>
                        </li>
                        <li>
                            <label for="ys">
                                <img src="../img/hp/sample_m.png" id="ys_icon" class="member_icon">
                                <h5>Y.S</h5>
                                <p>俳優</p>
                            </label>
                        </li>
                        <li>
                            <label for="is">
                                <img src="../img/hp/sample_m.png" id="is_icon" class="member_icon">
                                <h5>I.S</h5>
                                <p>エンターテイナー</p>
                            </label>
                        </li>
                        <li>
                            <label for="ik">
                                <img src="../img/hp/sample_m.png" id="ik_icon" class="member_icon">
                                <h5>I.K</h5>
                                <p>作家</p>
                            </label>
                        </li>
                    </ul>
                    <hr style="margin: 50px auto;">

                    <p class="title">手紙の画像をアップロード</p>
                    <div>
                        <label for="img-input">
                            <img src="../img/letter/imgup.png" id="upload-button">
                        </label>
                        <input type="file" name="letterimg" accept="image/*" id="img-input" style="display: none;">
                    </div>
                    <div id="img-preview">
                    </div>
                    <hr style="margin: 50px auto;">

                    <button type="submit" id="send">手紙を送る</button>
                </form>
            </div>
    </main>

    <footer>
        <hr size="1">
        <div class="copyright">
            <p>© FlowersKnives, Inc. All Rights Reserved.</p>
        </div>
    </footer>        

    
    <script>
        document.getElementById("img-input").addEventListener("change", function (event) {
        const preview = document.getElementById("img-preview");
        const file = event.target.files[0];
        const uploadButton = document.getElementById("upload-button");

        // プレビューエリアをクリア
        preview.innerHTML = "";

        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();

            // ファイル読み込み後の処理
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result; // 画像データURLを取得
                img.style.maxWidth = "70%";
                img.style.maxHeight = "70%";
                preview.appendChild(img); // プレビューエリアに追加

                // アップロードボタンを非表示にする
                uploadButton.style.display = "none";
            };

            reader.readAsDataURL(file); // ファイルをデータURLとして読み込む
        } else {
            preview.textContent = "手紙画像を選択してください。";
        }
    });
    </script>

</body>
</html>
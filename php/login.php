<?php
session_start();

// if (!isset($_SESSION["userID"])) {
//     header("Location: ../index.php");
//     exit;
// }

$file = fopen("../data/users.csv", "r");
flock($file, LOCK_SH);

if ($file) {

    while ($users = fgetcsv($file)) {

        // var_dump($line);

        if ($users[0] === $_POST["userID"]) {
            // echo "データベース：" . $users[0] . "POSTデータ" . $_POST["userID"] . "で一致しました";
            // 一致してたらセッションに保存
            $_SESSION["userID"] = $users[0];
            $_SESSION["userName"] = trim($users[1]);

            // ファイルを閉じて返す
            flock($file, LOCK_UN);
            fclose($file);

            header("Location: ../index.php");
            exit();
        } else {
            // echo "データベース：" . $users[0] . "POSTデータ" . $_POST["userID"] . "で不一致";
        }

        
    }
}

// 念のため 後々登録がなかった時の処理を作ったときに適切な位置に動かします
flock($file, LOCK_UN);
fclose($file);

exit();

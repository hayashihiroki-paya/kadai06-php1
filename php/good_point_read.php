<?php

session_start();
// echo "お気に入りデータ取得ページに移動できました";
// ログイン情報ない時終了
if (!isset($_SESSION["userID"])) {
    exit();
}

$userID = $_SESSION["userID"];
$isbn = $_POST["isbn"];

// 読み取りモードで開く
$file = fopen("../data/good_points.csv", "r");
flock($file, LOCK_SH);

// 連想配列化したデータを格納する変数
$goodPoints = [];

// $line => 0:userID,1:isbn,2:title,3:category,4:goodPoint
if ($file) {
    while ($line = fgetcsv($file)) {
        if ($line[0] === $userID && $line[1] === $isbn) {
            $goodPoints[] = [
                "category" => $line[3],
                "goodPoint" => $line[4]
            ];
        }
    }
    echo json_encode($goodPoints, JSON_UNESCAPED_UNICODE);
}

flock($file, LOCK_UN);
fclose($file);
exit();

<?php

session_start();
// echo "お気に入りデータ取得ページに移動できました";
// ログイン情報ない時終了
if (!isset($_SESSION["userID"])) {
    exit();
}

$userID = $_SESSION["userID"];

// POSTデータ受け取れるかチェック
if (
    !isset($_POST["isbn"]) ||
    !isset($_POST["title"]) ||
    !is_array($_POST["goodPoint"])
) {
    exit();
}


$isbn = $_POST["isbn"];
$title = $_POST["title"];
$goodPoint = $_POST["goodPoint"];
echo $isbn . "," . $title;
// var_dump($goodPoint); => 二次元配列 OK

// 書き込みモードで開く
$file = fopen("../data/good_points.csv", "a");
flock($file, LOCK_EX);

// $goodPointの配列を順番に書き込む
for ($i = 0; $i < count($goodPoint); $i++) {
    fwrite($file, $userID . "," . $isbn . "," . $title . ",");
    fputcsv($file, $goodPoint[$i]);
}

flock($file, LOCK_UN);
fclose($file);
exit();

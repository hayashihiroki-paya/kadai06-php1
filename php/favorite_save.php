<?php
// 文字列を受け取って改行文字などをスペースに置き換える関数
function sanitizeForCsv($str)
{
    return str_replace(["\r\n", "\r", "\n"], " ", $str);
}

session_start();
// echo "お気に入り登録ページに移動できました";
// $_POST["bookData"]ない、またはログイン情報ない時終了
if (!isset($_POST["bookData"]) || !isset($_SESSION["userID"])) {
    exit();
}

// 受け取ったデータ格納
$bookData = $_POST["bookData"];
// echo $bookData["itemCaption"];
$bookData["itemCaption"] = sanitizeForCsv($bookData["itemCaption"]);
// echo $bookData["itemCaption"];
$userID = $_SESSION["userID"];

// 重複チェック
// 読み取りモードで開く
$file = fopen("../data/favorites.csv", "r");
flock($file, LOCK_SH);

if ($file) {
    // fgetcsv($file)で配列にして 0番ユーザーID 3番isbn
    // csv構造
    // 0:userID 1:author 2:authorKana 3:isbn 4:itemCaption 5:largeImageUrl
    // 6:publisherName 7:salesDate 8:seriesName 9:title 10:titleKana 11:comment
    while ($line = fgetcsv($file)) {
        if ($line[0] === $userID && $line[3] === $bookData["isbn"]) {
            // echo "重複検知！！" . $line[0] . $userID . $line[3] . $bookData["isbn"];
            var_dump($line[11]);
            var_dump($bookData["comment"]);
            flock($file, LOCK_UN);
            fclose($file);
            exit();
        }

    }
}

// 以下、重複ない時の処理
// いったんファイル閉じる
flock($file, LOCK_UN);
fclose($file);


// 掻き込みモードで開く
$file = fopen("../data/favorites.csv", "a");
flock($file, LOCK_EX);
fwrite($file, $userID . ",");
fputcsv($file, $bookData);
flock($file, LOCK_UN);
fclose($file);
exit();



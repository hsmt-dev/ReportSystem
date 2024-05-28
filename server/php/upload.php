<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // アップロードされたファイルの情報を取得
    $file = $_FILES['image'];
    $title = $_POST['title'];
    $json_data = $_POST['json_data'];
    // アップロードされたファイルの一時保存パス
    $tmpFilePath = $file['tmp_name'];

    // 画像を保存するディレクトリ
    $uploadDir = 'entries/';

    // 一意のファイル名を生成 (投稿日時を使用)
    $timestamp = time();
    $fileName = $timestamp . '_' . $file['name'];

    // レスポンスデータを作成
    $response = $timestamp;

    // 画像の保存先パス
    $destination = $uploadDir . $fileName;

    // 画像の移動と保存
    if (move_uploaded_file($tmpFilePath, $destination)) {

        // 投稿データをJSON形式で保存
        $postData = [
            'post_date' => date('Y-m-d H:i:s', $timestamp),
            'title' => $title,
            'image' => $destination,
            'json_data' => $json_data,
        ];

        // 投稿データをJSON形式で保存
        file_put_contents($uploadDir . $timestamp . '.json', json_encode($postData));

        header('Content-Type: text/plain');
        echo $response;

        // 投稿一覧ページにリダイレクト
        //header('Location: index.php');
        //exit();
    } else {
        echo 'ファイルのアップロードに失敗しました。';
    }
}
?>

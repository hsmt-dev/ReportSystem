<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // アップロードされたファイルの情報を取得
    $file = $_FILES['image'];
    $title = $_POST['title'];
    //$post_date = $_POST['post_date'];

    $date_time = $_POST['date_time'];
    $operating_system = $_POST['operating_system'];
    $device_model = $_POST['device_model'];
    $system_memory_size = $_POST['system_memory_size'];
    $use_memory_size = $_POST['use_memory_size'];

    // アップロードされたファイルの一時保存パス
    $tmpFilePath = $file['tmp_name'];

    // 画像を保存するディレクトリ
    $uploadDir = 'entries/';

    // 一意のファイル名を生成 (投稿日時を使用)
    $timestamp = time();
    $fileName = $timestamp . '_' . $file['name'];

    // 画像の保存先パス
    $destination = $uploadDir . $fileName;

    // 画像の移動と保存
    if (move_uploaded_file($tmpFilePath, $destination)) {

        // 投稿データをJSON形式で保存
        $postData = [
            'post_date' => date('Y-m-d H:i:s', $timestamp),
            'title' => $title,
            'image' => $destination,

            'date_time' => $date_time,
            'operating_system' => $operating_system,
            'device_model' => $device_model,
            'system_memory_size' => $system_memory_size,
            'use_memory_size' => $use_memory_size,
        ];

        // 投稿データをJSON形式で保存
        file_put_contents($uploadDir . $date_time . '.json', json_encode($postData));

        // 投稿一覧ページにリダイレクト
        header('Location: index.php');
        exit();
    } else {
        echo 'ファイルのアップロードに失敗しました。';
    }
}
?>

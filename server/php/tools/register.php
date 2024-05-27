<?php

require_once '../utils/LoginUtil.php';

// ユーザーデータファイルのパス
const USERS_DATA_FILE = '../users/users.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $user_id = trim($_POST['user_id']);
    $password = trim($_POST['password']);

    if( LoginUtil::Register( USERS_DATA_FILE, $user_id, $password ) )
    {
        echo 'ユーザー登録が完了しました。<br>';
        echo '<a href="../index.php">ログインページに戻る</a><br>';
        exit;
    }

    // 登録失敗：既存ユーザーIDが見つかった
    echo 'このユーザーIDは既に登録されています。別のIDを選んでください。<br>';
    echo '<a href="register.php">戻る</a><br>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
</head>
<body>
    <h2>ユーザー登録</h2>
    <form method="post" action="register.php">
        <label for="user_id">ユーザーID:</label>
        <input type="text" id="user_id" name="user_id" required><br>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="登録">
    </form>
</body>
</html>
